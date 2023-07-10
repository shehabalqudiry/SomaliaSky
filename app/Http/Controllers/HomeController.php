<?php

namespace App\Http\Controllers;

use FCM;
use App\Models\Chat;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $chats = Chat::get();
        return view('home', compact('chats'));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('front.user.my-profile', compact('user'));
    }

    public function profile_edit()
    {
        $profile = auth()->user();
        return view('front.user.edit-profile', compact('profile'));
    }

    public function profile_update(Request $request)
    {
        $profile = auth()->user();
        $request->validate([
            'name'=>"required|unique:users,name," . $profile->id,
            'phone'=>"required|unique:users,phone," . $profile->id,
            'email'=>"required|unique:users,email," . $profile->id,
            'City'=>"required|exists:cities,id",
            'Country'=>"nullable",
            'password'=>"nullable|min:8|max:255",
            'Avatar'            => "image|mimes:png,jpg,jpeg|max:2048",
        ]);

        $profile->update([
            'name' => $request['name'],
            'phone' => $request['phone'],
            'email' => $request['email'],
            'city_id' => $request['City'],
            'password' => $request['password'] ? Hash::make($request['password']) : $profile->password,
        ]);

        if ($request->hasFile('Avatar')) {
            $file = $this->store_file([
                'source'=>$request->Avatar,
                'validation'=>"image",
                'path_to_save'=>"/uploads/users/",
                'type'=>'CATEGORIES',
                'user_id'=>\Auth::user()->id,
                'resize'=>[500,1000],
                'small_path'=>'small/',
                'visibility'=>'PUBLIC',
                'file_system_type'=>env('FILESYSTEM_DRIVER'),
                /*'watermark'=>true,*/
                'compress'=>'auto'
            ]);
            $profile->update(['avatar'=>$file['filename']]);
        }

        flash()->success(__('lang.The action ran successfully!'));
        return redirect()->route('front.profile');
    }

    public function createChat(Request $request)
    {
        $input = $request->all();
        $message = $input['message'];
        $chat = new Chat([
            'sender_id' => auth()->user()->id,
            'user_id' => $user_id,
            'sender_name' => auth()->user()->name,
            'message' => $message
        ]);

        // $this->broadcastMessage(auth()->user()->name, $message);

        // $chat->save();
        return redirect()->back();
    }

    private function broadcastMessage($senderName, $message)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder('New message from : ' . $senderName);
        $notificationBuilder->setBody($message)
        ->setSound('default')
        ->setClickAction('http://localhost:8000/home');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData([
        'sender_name' => $senderName,
        'mesage' => $message
    ]);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $tokens = User::all()->pluck('fcm_token')->toArray();

        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);

        return $downstreamResponse->numberSuccess();
    }
}
