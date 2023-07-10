<?php

namespace App\Http\Controllers\Api\Users;

use App\Models\Chat;
use App\Models\User;
use App\Helpers\MainHelper;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\{CityResource, StateResource, ChatResource};

class ChatApi extends Controller
{
    use GeneralTrait;
    public function get_all_chats(Request $request)
    {
        $user_id = auth()->user()->id;
        if ($request->chat_id) {
            // $chat = Chat::find($request->chat_id);
            $chat = Chat::where('id', $request->chat_id)->with('messages', 'sender', 'user')->first();

            return $this->returnData("data", $chat, "Chat");
        }
        $chats = Chat::where('user_id', $user_id)->orWhere('sender_id', $user_id)->with('messages', 'sender', 'user')->latest('updated_at')->get();

        return $this->returnData("data", $chats, "All Chats");
    }

    public function delete(Request $request)
    {
        if (!$request->Chat_id) {
            return $this->returnError(false, 404, "Chat id is required");
        }

        $chat = Chat::find($request->Chat_id)->delete();
        return $this->returnSuccessMessage(__('lang.The :resource was deleted!', ['resource' => __('lang.messages')]));
    }


    public function send_msg(Request $request)
    {
        $id = $request->Chat_id ?? null;
        $user_id = $request->user_id;
        $msg = $request->msg;
        try {
            $user = User::find($user_id);
            $chat = Chat::find($id);
            if (!$chat) {
                $chat = Chat::create([
                    'sender_id'     => auth()->user()->id,
                    'user_id'       => $user_id,
                ]);
            }
            $chat->messages()->create([
                'message'       => $msg,
                'sender_id'     => auth()->user()->id,
                'user_id'       => $user_id,
            ]);
            $chat->update(['updated_at' => now()]);
            (new MainHelper())->notify_user([
                'user_id' => $user_id,
                'message'=> __("lang.New Message") . "  " . __('lang.from') . "  " . request()->user()->name ,
                'url'=> route('front.chat'),
                'methods'=>['database']
            ]);
            sendmessage($user->fcm_token, __('lang.New Message') .' ' . __('lang.from') . ' ' . $user->name, $msg);
            return $this->returnData('data', $chat, __('lang.The action ran successfully!'));
        } catch (\Exception $e) {
            return $this->returnError(false, 500, $e->getMessage());
        }
    }
}
