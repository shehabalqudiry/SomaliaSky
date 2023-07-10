<div wire:poll>
    <div class="container my-3">
        <h5 class="d-inline-block me-4">{{ __('lang.skills') }}</h5>
        @forelse ($user->skills as $skill)
            <span wire:click="formEdit({{ $skill->id }})" style="border-radius: 10px"
                class="shadow font-1 badge bg-light px-4 py-2 text-dark mx-2 mb-3">{{ $skill->name }} <i
                    class="fa fa-edit"></i></span>
            <button class="btn btn-danger btn-sm" wire:click="delete({{ $skill->id }})">
                <i class="fa fa-times"></i>
            </button>
            @empty
            <div class="text-center mb-4">
                {{ __('lang.No Current Data') }}
            </div>
        @endforelse
        @if (auth()->check() and auth()->user()->id == $user->id)

                @if ($formEditShow)
                    <span style="border-radius: 10px" class="shadow font-1 badge px-4 py-2 text-dark mx-3 mainBgColor">
                        <input type="text" required placeholder="{{ __('lang.skills') }}"
                            class="rad14 form-control @error('eskill') is-invalid @enderror" wire:model="eskill">
                        <button wire:click="editskill" class="btn mainBgColor">
                            {{ __('lang.Edit') }}
                        </button>
                    </span>
                @endif
                @if ($formShow == 0)
                    <span wire:click="$set('formShow', 1)" style="border-radius: 10px"
                        class="shadow font-1 badge px-4 py-2 text-dark mx-3 mainBgColor">
                        {{ __('lang.skills') }}
                        <i class="fa fa-plus"></i>
                    </span>
                @else
                    <span style="border-radius: 10px" class="shadow font-1 badge px-4 py-2 text-dark mx-3 mainBgColor">
                        <input type="text" placeholder="{{ __('lang.skills') }}"
                            class="rad14 form-control @error('skill') is-invalid @enderror" wire:model="skill">
                        <button wire:click="addskill" class="btn mainBgColor">
                            {{ __('lang.Apply') }}
                        </button>
                    </span>
                @endif
        @endif
    </div>

</div>
