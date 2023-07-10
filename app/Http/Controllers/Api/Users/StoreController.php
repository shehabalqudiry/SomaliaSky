<?php

namespace App\Http\Controllers\Api\Users;

use App\Models\City;
use App\Models\Rate;
use App\Models\User;
use App\Models\Store;
use App\Models\Category;
use App\Models\Currency;
use App\Helpers\MainHelper;
use App\Models\Announcement;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\StoreResource;
use App\Models\AnnouncementAttribute;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\AnnouncementResource;

class StoreController extends Controller
{
    use GeneralTrait;


    public function get_my_profile(Request $request)
    {
        $user = User::find($request->user()->id);
        $data = new UserResource($user);
        return $this->returnData('data', $data, 'تمت العملية بنجاح');
    }
    public function my_profile(Request $request)
    {
        $user = User::find($request->user()->id);
        $rules = [
            'name'=>"nullable|max:255",
            'phone'=>"nullable|unique:users,phone,". $request->user()->id,
            'email'=>"required|unique:users,email,". $request->user()->id,
            'avatar'  => "nullable|image|mimes:png,jpg,jpeg|max:5114",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        try {
            $user->update([
                "name"=>$request->name,
                "phone"=>$request->phone,
                "email"=>$request->email,
                "city_id"=>$request->city_id,
            ]);

            if ($request->hasFile('avatar')) {
                $file = $this->store_file([
                    'source'=>$request->avatar,
                    'validation'=>"image",
                    'path_to_save'=>'/uploads/users/',
                    'type'=>'USER',
                    'user_id'=>\Auth::user()->id,
                    'resize'=>[500,1000],
                    'small_path'=>'small/',
                    'visibility'=>'PUBLIC',
                    'file_system_type'=>env('FILESYSTEM_DRIVER'),
                    'watermark'=>true,
                    'compress'=>'auto'
                ]);
                $user->update(['avatar'=>$file['filename']]);
            }

            $data = new UserResource($user);
            return $this->returnData('data', $data, 'تمت العملية بنجاح');
        } catch (\Exception $e) {
            return $this->returnError('001', __('lang.Server Error'));
        }
    }
    public function users(Request $request)
    {
        if ($request->id) {
            $data = User::where('blocked', 0)->where('id', $request->id)->first();
            if (!$data) {
                return $this->returnError(404, __('lang.Not Found'));
            }
            $data = new UserResource($data);
        } else {
            $data = User::where('blocked', 0)->latest()->get();
            if (!count($data)) {
                return $this->returnData('data', $data, 'users');
            }
            $data = UserResource::collection($data);
        }

        return $this->returnData('data', $data, 'users');
    }
    public function rate(Request $request)
    {
        // return $request->all();
        $validator = Validator::make($request->all(), [
            'stars'             => "required|in:1,2,3,4,5",
            'user_id'           => "required|exists:users,id",
        ]);
        if ($validator->fails()) {
            return $this->returnError(404, $validator->errors()->first());
        }

        $rate = Rate::where([
            'user_id'  => $request->user()->id,
            'rated_user'  => $request->user_id,
        ])->first();

        if (!$rate) {
            $rate = Rate::create([
                'stars'  => $request->stars,
                'user_id'  => $request->user()->id,
                'rated_user'  => $request->user_id,
            ]);
        } else {
            $rate->update(['stars' => $request->stars]);
        }
        return $this->returnSuccessMessage(__('lang.Done.'));
    }

    public function create_store(Request $request)
    {
        if ($request->user()->store) {
            return $this->returnError(401, 'لديك متجر بالفعل لا يسمح بإضافة اكثر من متجر لنفس الحساب');
        }
        $request->merge([
            'slug'=>\MainHelper::slug($request->Name)
        ]);

        $validator = Validator::make($request->all(), [
            'Name'              => "required|string|unique:stores,name",
            'Category'          => "required|exists:categories,id",
            'City'              => "required|exists:cities,id",
            'Description'       => "nullable|max:100000",
        ]);
        if ($validator->fails()) {
            return $this->returnError(404, $validator->errors()->first());
        }
        try {
            $store = Store::create([
                'name'              => $request->Name,
                'user_id'           => $request->user()->id,
                'category_id'       => $request->Category,
                'city_id'           => $request->City,
                'slug'              => $request->slug,
                'location'          => $request->Location,
                'description'       => $request->Description,
                'blocked'           => 2,
            ]);

            if ($request->hasFile('Avatar')) {
                $file = $this->store_file([
                    'source'=>$request->Avatar,
                    'validation'=>"image",
                    'path_to_save'=>"/uploads/stores/$store->id/",
                    'type'=>'ARTICLE',
                    'user_id'=>\Auth::user()->id,
                    'resize'=>[500,1000],
                    'small_path'=>'small/',
                    'visibility'=>'PUBLIC',
                    'file_system_type'=>env('FILESYSTEM_DRIVER'),
                    /*'watermark'=>true,*/
                    'compress'=>'auto'
                ]);
                $store->update(['avatar'=>$file['filename']]);
            }

            if ($request->hasFile('Cover')) {
                $file = $this->store_file([
                    'source'=>$request->Cover,
                    'validation'=>"image",
                    'path_to_save'=>"/uploads/uploads/",
                    'type'=>'ARTICLE',
                    'user_id'=>\Auth::user()->id,
                    'resize'=>[500,1000],
                    'small_path'=>'small/',
                    'visibility'=>'PUBLIC',
                    'file_system_type'=>env('FILESYSTEM_DRIVER'),
                    /*'watermark'=>true,*/
                    'compress'=>'auto'
                ]);
                $store->update(['cover'=>$file['filename']]);
            }

            (new MainHelper())->notify_user([
                'user_id' => 1,
                'message'=> "طلب فتح متجر جديد : $store->title \n للتاجر : " . $request->user()->name ,
                'url'=> route('admin.stores.index', ['id' => $store->id]),
                'methods'=>['database']
            ]);

            return $this->returnData('data', $store, __('lang.Created.'));
        } catch (\Exception $e) {
            return $this->returnError(404, __('lang.Server Error'));
        }
    }


    public function update_store(Request $request)
    {
        $store = $request->user()->store;
        $request->merge([
            'slug'=>\MainHelper::slug($request->Name)
        ]);

        $validator = Validator::make($request->all(), [
            'Name'              => "required|string|unique:stores,name," . $store->id,
            'Category'          => "required|exists:categories,id",
            'City'              => "required|exists:cities,id",
            'Description'       => "nullable|max:100000",
        ]);
        if ($validator->fails()) {
            return $this->returnError(404, $validator->errors()->first());
        }
        try {
            $store->update([
                'name'              => $request->Name,
                'category_id'       => $request->Category,
                'city_id'           => $request->City,
                'slug'              => $request->slug,
                'location'          => $request->Location,
                'description'       => $request->Description,
                // 'blocked'           => 2,
            ]);

            if ($request->hasFile('Avatar')) {
                $file = $this->store_file([
                    'source'=>$request->Avatar,
                    'validation'=>"image",
                    'path_to_save'=>"/uploads/stores/$store->id/",
                    'type'=>'ARTICLE',
                    'user_id'=>\Auth::user()->id,
                    'resize'=>[500,1000],
                    'small_path'=>'small/',
                    'visibility'=>'PUBLIC',
                    'file_system_type'=>env('FILESYSTEM_DRIVER'),
                    /*'watermark'=>true,*/
                    'compress'=>'auto'
                ]);
                $store->update(['avatar'=>$file['filename']]);
            }

            if ($request->hasFile('Cover')) {
                $file = $this->store_file([
                    'source'=>$request->Cover,
                    'validation'=>"image",
                    'path_to_save'=>"/uploads/uploads/",
                    'type'=>'ARTICLE',
                    'user_id'=>\Auth::user()->id,
                    'resize'=>[500,1000],
                    'small_path'=>'small/',
                    'visibility'=>'PUBLIC',
                    'file_system_type'=>env('FILESYSTEM_DRIVER'),
                    /*'watermark'=>true,*/
                    'compress'=>'auto'
                ]);
                $store->update(['cover'=>$file['filename']]);
            }

            // (new MainHelper())->notify_user([
            //     'user_id' => 1,
            //     'message'=> "طلب فتح متجر جديد : $store->title \n للتاجر : " . $request->user()->name ,
            //     'url'=> route('admin.stores.index', ['id' => $store->id]),
            //     'methods'=>['database']
            // ]);

            return $this->returnData('data', $store, __('lang.The action ran successfully!'));
        } catch (\Exception $e) {
            return $this->returnError(404, __('lang.Server Error'));
        }
    }


    public function my_store(Request $request)
    {
        $store = Store::where('user_id', $request->user()->id)->first();
        if (!$store) {
            return $this->returnError(404, __('lang.Not Found'));
        }
        $store = new StoreResource($store);
        return $this->returnData('data', $store, 'My Store');
    }

    public function stores(Request $request)
    {
        // $store = Store::where('blocked', 0)->latest()->get();
        $categories = [];
        $subCats = [];
        $category = Category::where('id', $request->Category)->first();

        if ($category) {
            foreach ($category->subCats as $cat) {
                $subCats[] = $cat->id;
            }
        }
        $stores = Store::where('blocked', 0)->where(function ($q) use ($request, $subCats) {
            if ($request->id!=null) {
                $q->where('id', $request->id);
            }
            if ($request->q!=null) {
                $q->where('name', 'LIKE', '%'.$request->q.'%')->orWhere('description', 'LIKE', '%'.$request->q.'%');
            }

            if ($request->city!=null) {
                $q->where('city_id', $request->city);
            }

            if ($request->country!=null) {
                $q->whereHas('city', function ($qs) use ($request) {
                    return $qs->where('country_id', '=', $request->country);
                })->get();
            }

            if ($request->category_id) {
                $q->where('category_id', $request->category_id);
            }

            if ($request->Category!=null) {
                $q->whereHas('category', function ($qs) use ($request) {
                    return $qs->where('parent_id', '=', $request->Category)->orWhere('id', $request->Category);
                });
            }
        })->latest();

        try {
            $city = get_user_country($request);
        } catch (\Exception $e) {
            $city = ['city'=>null];
        }

        if ($request->user() && (!$request->country || !$request->city)) {
            $stores = $stores->where('city_id', $request->user()->city_id);
        } elseif (!$request->country && !$request->city) {
            $ci = $city != false ? $city['city'] : 'n';
            $cityTrans = trans("lang." . $ci);
            $city = City::where('name', 'LIKE', "%" . $ci . "%")->orWhere('name', 'LIKE', "%" . $cityTrans . "%")->first();
            $stores->where('city_id', $city->id ?? 0);
        }
        $store = StoreResource::collection($stores->get());
        return $this->returnData('data', $store, 'All Stores');
    }


    public function announcements(Request $request)
    {
        if ($request->id) {
            $announcement = Announcement::whereRelation('store', 'blocked', 0)->where([['status', '!=', 0], 'id' => $request->id])->first();
            $shareButtons = route('front.announcements.show', $announcement);
            if ($announcement) {
                $announcement->share_link = $shareButtons;
                $data = new AnnouncementResource($announcement);
                return $this->returnData('data', $data, "تفاصيل الاعلان : $request->id");
            }
            return $this->returnError(404, 'الاعلان غير موجود');
        } else {
            $categories = [];
            $subCats = [];
            $category = Category::where('slug', $request->Category)->first();
            if ($category) {
                foreach ($category->subCats as $cat) {
                    $subCats[] = $cat->id;
                }
            }
            $announcements = Announcement::where('status', '!=', 0)->where(function ($q) use ($request) {
                if ($request->id!=null) {
                    $q->where('id', $request->id);
                }
                if ($request->q!=null) {
                    $q->where('title->'.app()->getLocale(), 'LIKE', '%'.$request->q.'%')->orWhere('description->'.app()->getLocale(), 'LIKE', '%'.$request->q.'%');
                }

                if ($request->category_id!=null) {
                    $q->where('category_id', $request->category_id);
                }

                if ($request->from!=null || $request->to != null) {
                    $q->whereBetween('price', [$request->from, $request->to]);
                }


                if ($request->city!=null) {
                    $q->where('city_id', $request->city);
                }


                if ($request->is_featured) {
                    $q->where('is_featured', '!=', 0);
                }
            })->orderBy('is_featured', 'DESC')->get();

            try {
                $city = get_user_country($request);
            } catch (\Exception $e) {
                $city = ['city'=>null];
            }

            $is_featured = AnnouncementResource::collection($announcements->where('is_featured', '!=', 0));
            $announcements = AnnouncementResource::collection($announcements);
            $data = [
                'is_featured' => $is_featured,
                'announcements' => $announcements,
            ];
            return $this->returnData('data', $data, "الاعلانات");
        }
    }

    public function my_announcements(Request $request)
    {
        $announcements = Announcement::where('user_id', $request->user()->id)->get();
        $announcements = AnnouncementResource::collection($announcements);
        return $this->returnData('data', $announcements, 'All My Announcements');
    }

    public function create_announcement(Request $request)
    {
        $data = $request->all();
        $sub = $request->user()->subscription->where('status', 1)->first();
        // dd($sub->package->announcement_number - $request->user()->announcements->count() < 1);
        if ($sub->package->announcement_number - $request->user()->announcements->count() < 1) {
            return $this->returnError("L1001", __('lang.limited'));
        }

        try {
            if (!Category::where(['id'=> $request->Category, ['parent_id', "!=", null]])->first()) {
                return $this->returnError(404, __('lang.Not Found'));
            }

            $validator = Validator::make($request->all(), [
                'Category'=>"required|exists:categories,id",
                'City'=>"required|exists:cities,id",
                'Price'=>"required",
            ]);
            if ($validator->fails()) {
                return $this->returnError(404, $validator->errors());
            }


            foreach (config("laravellocalization.supportedLocales") as $key => $lang) {
                // Rules
                // $rules["$key.title"] = "string|max:255";
                // Lang
                $langAttr["Title"][$key] = $data[$key]['Title'];
                $langAttr["Description"][$key] = $data[$key]['Description'];
            }

            DB::beginTransaction();
            $user = $request->user();
            $announcement = Announcement::create([
                "user_id"           => $user->id,
                "store_id"          => $user->store ? $user->store->id : $user->id,
                "category_id"       => intval($request->Category),
                "currency_id"       => $request->currency_id ?? Currency::first()->id,
                "city_id"           => intval($request->City),
                "type"              => $request->type,
                "title"             => $langAttr["Title"],
                "description"       => $langAttr["Description"],
                "is_featured"       => 0,
                "number"            => $this->generateAnnouncementNumber(),
                "price"             => $request->Price,
                "status"            => 0,
            ]);

            if ($request->announcement_attributes) {
                // dd($request->announcement_attributes);
                foreach ($request->announcement_attributes as $value) {
                    $announcement_attr = AnnouncementAttribute::create([
                        "name" => $value['attr'],
                        "value" => $value['value'],
                        "announcement_id" => $announcement->id,
                    ]);
                }
            }

            if ($request->Images) {
                $attachments=[];
                foreach ($request->Images as $img) {
                    $file = $this->store_file([
                        'source'=>$img,
                        'validation'=>"image",
                        'path_to_save'=>"/uploads/uploads/",
                        'type'=>'ARTICLE',
                        'user_id'=>\Auth::user()->id,
                        'resize'=>[500,1000],
                        'small_path'=>'small/',
                        'visibility'=>'PUBLIC',
                        'file_system_type'=>env('FILESYSTEM_DRIVER'),
                        /*'watermark'=>true,*/
                        'compress'=>'auto'
                    ]);
                    $attachments[] = $file['filename'];
                    // return $request->img;
                }
                // return $attachments;
                $announcement->update(['images' => implode(',', $attachments)]);
            }
            try {
                (new MainHelper())->notify_user([
                    'user_id' => 1,
                    'message'=> "تم اضافة اعلان جديد في انتظار الموافقة : $announcement->title \n للتاجر : " . $request->user()->name ,
                    'url'=> route('admin.announcements.index', ['id' => $announcement->id]),
                    'methods'=>['database']
                ]);
            } catch (\Throwable $th) {
                //throw $th;
            }
            $announcement = new AnnouncementResource($announcement);
            DB::commit();
            return $this->returnData('data', $announcement, __('lang.Created.'));
        } catch (\Exception $e) {
            DB::rollback();
            // return $this->returnError(500, __('lang.Server Error'));
            return $this->returnError(500, $e->getMessage());
        }
    }
}
