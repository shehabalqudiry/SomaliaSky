<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Models\Package;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use GeneralTrait;

    public function register(Request $request)
    {
        $rules = [
            'name'=>"nullable|max:255",
            'phone'=>"nullable|unique:users,phone",
            'email'=>"required|unique:users,email",
            'city_id'=>"required|exists:cities,id",
            'password'=>"required|min:8|max:255",
            'fcm_token'=>"nullable",
            'avatar'  => "nullable|image|mimes:png,jpg,jpeg|max:5114",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        try {
            $user = User::create([
                "name"=>$request->name,
                "phone"=>$request->phone,
                "city_id"=>$request->city_id,
                "email"=>$request->email,
                "fcm_token"=>$request->email,
                "password"=>\Hash::make($request->password),
            ]);

            if ($request->hasFile('avatar')) {
                $file = $this->store_file([
                    'source'=>$request->avatar,
                    'validation'=>"image",
                    'path_to_save'=>'/uploads/users/',
                    'type'=>'USER',
                    'user_id'=>null,
                    'resize'=>[500,1000],
                    'small_path'=>'small/',
                    'visibility'=>'PUBLIC',
                    'file_system_type'=>env('FILESYSTEM_DRIVER'),
                    'watermark'=>true,
                    'compress'=>'auto'
                ]);
                $user->update(['avatar'=>$file['filename']]);
            }

            $credentials = $request->only(['email', 'password']);
            $token = auth()->attempt($credentials);
            $token = $user->createToken($request->ip())->plainTextToken;
            $package = Package::where('price', 0)->first();
            if ($package) {
                $user->subscription()->create([
                    'package_id'        => $package->id,
                    'price'             => $package->price,
                    'status'            => 1,
                    'paid'              => 1,
                    'start_date'        => now(),
                    'end_date'          => now()->addDays($package->time),
                ]);
            }
            $user->api_key = $token;
            $data = new UserResource($user);
            return $this->returnData('data', $data, 'تمت العملية بنجاح');
        } catch (\Exception $e) {
            return $this->returnError('001', $e->getMessage());
        }
    }

    public function login(Request $request)
    {
        try {
            $rules = [
                    "email"     => "required",
                    "password"  => "required",
                    "fcm_token" => "nullable",

                ];

            $validator = Validator::make($request->all(), $rules, [
                    'email.required' => 'حقل البريد الالكتروني مطلوب',
                    'password.required' => 'حقل كلمة السر مطلوب',
                ]);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            if (!\Auth::attempt($request->only('email', 'password'))) {
                return $this->returnError(402, "خطأ في البريد الالكتروني او كلمة المرور");
            }

            $user = \Auth::user();
            $user->update([
                'fcm_token'=> $request->fcm_token,
            ]);
            $token = $user->createToken($request->password)->plainTextToken;
            $user->api_key = $token;
            $data = new UserResource($user);
            return $this->returnData('data', $data, 'تسجيل دخول ناجح');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function logout()
    {
        request()->user()->currentAccessToken()->delete();
        return $this->returnSuccessMessage('تم الخروج من التطبيق');
    }
}
