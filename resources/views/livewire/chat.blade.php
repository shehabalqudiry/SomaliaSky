<div class="row justify-content-center align-items-start">

    <aside class="col-12 col-lg-4 rounded-4 border card">
        <div class="card-body px-0" wire:poll>
            <h3>{{ __('lang.messages') }}</h3> {{ !$chats->count() ? __('lang.No Data') : '' }}
            <ul class="mt-3 pr-0" id="userMenue">
                @foreach ($chats as $chat)
                    @if ($chat->messages->count())
                        @if ($chat->user && $chat->sender)
                            <li class="{{ $chat->id == $chatId->id ? 'mainBgColor' : '' }} border rounded-2 d-flex"
                                wire:click="chatShow({{ $chat->id }})" style="justify-content: space-between">
                                <div class="">
                                    <img src="{{ $chat->sender_id == $user->id ? $chat->user->getUserAvatar() : $chat->sender->getUserAvatar() }}"
                                        alt="" width="60" height="60">
                                    <div>
                                        <h6>{{ $chat->sender_id == $user->id ? $chat->user->name : $chat->sender->name }}
                                        </h6>
                                    </div>
                                </div>
                                <div class="">
                                    <button class="btn text-end font-3 text-danger"
                                        wire:click="delete({{ $chat->id }})"><span
                                            class="fal fa-trash"></span></button>
                                </div>
                            </li>
                        @endif
                    @endif
                @endforeach
            </ul>
        </div>
    </aside>
    <main class="col-12 col-lg-6 card ms-2" wire:poll>
        <ul id="chat" class="card-body">
            @if ($chatId && $chatId->user && $chatId->sender)
                @foreach ($chatId->messages as $message)
                    <li class="{{ $message->sender_id == $user->id ? 'me' : 'you' }} my-3">
                        <div class="entete mt-2">
                            <span><img
                                    src="{{ $message->sender_id == $user->id ? $message->sender->getUserAvatar() : $message->user->getUserAvatar() }}"
                                    alt="" width="40" height="40" class="rounded-circle"></span>
                            <span style="font-size: 15px !important">{{ $message->created_at->diffforhumans() }}</span>
                        </div>
                        <div style="font-size: 15px !important"
                            class="{{ $message->sender_id == $user->id ? 'mainBgColor' : '' }}  border rad14 message text-start">
                            {!! $message->message !!}
                        </div>
                    </li>
                @endforeach
            @endif
        </ul>
        <div class="col-12 my-3">
            <form wire:submit.prevent="send_msg" class="row" style="justify-content: space-between">
                <input wire:model="msg" class="col-8 px-3 py-2 border rad14 d-inline-block"
                    placeholder="{{ __('lang.Send') }}">
                <button class="col-3 btn d-inline-block rad14 mainBgColor" type="submit">{{ __('lang.Send') }} <i
                        class="fas fa-paper-plane"></i> </button>
                @error('msg')
                    <span class="d-block text-danger w-100 py-1">{{ $message }}</span>
                @enderror
            </form>
        </div>
    </main>
</div>
