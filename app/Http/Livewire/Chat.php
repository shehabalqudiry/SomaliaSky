<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Helpers\MainHelper;
use App\Models\Announcement;
use App\Models\Chat as ModelChat;

class Chat extends Component
{
    public $chatId;
    public $msg;
    public $user;
    public $user_id;
    public $chat_show = 0;

    public function __construct($user_id = null)
    {
        $this->user = auth()->user();
        if (request()->user_id && request()->announcement_number) {
            $user_id = request()->user_id;
            $chat1 = ModelChat::where('user_id', $this->user->id)
                                ->where('sender_id', $user_id)->first();

            $chat2 = ModelChat::where('user_id', $user_id)
                                ->where('sender_id', $this->user->id)
                                ->first();
            // dd(!$chat1 && !$chat2);
            $chat = !$chat1 ? $chat2 : $chat1;
            if (!$chat) {
                $chat = ModelChat::create([
                    'sender_id' => $this->user->id,
                    'user_id' => $user_id,
                ]);
            }
            $chat->messages()->create([
                'sender_id' => $this->user->id,
                'user_id' => $user_id,
                'message'   => "<a class='font-2' style='color: #fff' href='".route('front.announcements.show', request()->announcement_number) . "'><h6>". (Announcement::where('number', request()->announcement_number)->first()->title ?? '') . "<br /><br />" . __('lang.Announcement Code'). " : " .request()->announcement_number."</h6></a>"
            ]);
            if (!$this->chatId) {
                $this->user_id = $user_id;
                $this->chatId = $chat;
            }
            try {
                //code...
                (new MainHelper())->notify_user([
                    'user_id' => $this->user_id,
                    'message'=> __("lang.New Message") . "  " . __('lang.from') . "  " . request()->user()->name ,
                    'url'=> route('front.chat'),
                    'methods'=>['database']
                ]);
            } catch (\Throwable $th) {
                //throw $th;
            }
            $this->chat_show = 1;
        } else {
            $chats = ModelChat::where('user_id', $this->user->id)->orWhere('sender_id', $this->user->id)->with('messages', 'sender', 'user')->get();
            if ($chats->count()) {
                $this->chatId = $chats->first();
                $this->chat_show = 1;
            }
        }
    }

    // public function updating()
    // {
    //     $this->emit('update');
    // }

    protected $rules = [
        'msg' => 'string',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function chatShow($id)
    {
        $chat = ModelChat::where('id', $id)->first();
        $this->user_id = $chat->user_id == $this->user->id ? $chat->sender_id : $chat->user_id;
        // dd($idUser);
        // $this->user_id = $idUser;
        $this->chatId = $chat;
        $this->chat_show = 1;
        // dd($this->chatId);
        $this->emit('update');
    }

    public function delete($id)
    {
        $this->chat_show = 0;
        $this->chatId = '';
        $chat = ModelChat::find($id)->delete();
        // $this->$refresh;
    }

    public function send_msg()
    {
        $msg = $this->validate();
        $this->chatId->messages()->create([
            'message'       => $msg['msg'],
            'sender_id'     => $this->user->id,
            'user_id'       => $this->user_id,
        ]);
        $this->chatId->update(['updated_at' => now()]);
        try {
            //code...
            (new MainHelper())->notify_user([
                'user_id' => $this->user_id,
                'message'=> __("lang.New Message") . "  " . __('lang.from') . "  " . request()->user()->name ,
                'url'=> route('front.chat'),
                'methods'=>['database']
            ]);
            $user = User::find($this->user_id);
            sendmessage($this->user->fcm_token, __('lang.New Message') .' ' . __('lang.from') . ' ' . $user->name, $msg);
        } catch (\Throwable $th) {
            //throw $th;
        }
        $this->emit('update');
        $this->msg = '';
    }
    public function render()
    {
        $chats = ModelChat::where('user_id', $this->user->id)->orWhere('sender_id', $this->user->id)->with('messages', 'sender', 'user')->latest('updated_at')->get();
        // if ($chats->count()) {
        //     $this->chatId = $chats->first();
        //     $this->chat_show = 1;
        // }
        return view('livewire.chat', ['chats' => $chats]);
    }
}
