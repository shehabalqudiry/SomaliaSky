<div wire:poll>
    <div class="container my-3">
        <h5 class="d-inline-block me-4">{{ __('lang.job') }}</h5>
        @forelse ($user->jobs as $job)
            <span wire:click="formEdit({{ $job->id }})" style="border-radius: 10px"
                class="shadow font-1 badge bg-light px-4 py-2 text-dark mx-2 mb-3">{{ $job->name }} <i
                    class="fa fa-edit"></i></span>
            <button class="btn btn-danger btn-sm" wire:click="delete({{ $job->id }})">
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
                <input type="text" required placeholder="{{ __('lang.job') }}"
                    class="rad14 form-control @error('ejob') is-invalid @enderror" wire:model="ejob">
                <button wire:click="editJob" class="btn mainBgColor">
                    {{ __('lang.Edit') }}
                </button>
            </span>
        @endif
        @if ($formShow == 0)
            <span wire:click="$set('formShow', 1)" style="border-radius: 10px"
                class="shadow font-1 badge px-4 py-2 text-dark mx-3 mainBgColor">
                {{ __('lang.job') }}
                <i class="fa fa-plus"></i>
            </span>
        @else
            <span style="border-radius: 10px" class="shadow font-1 badge px-4 py-2 text-dark mx-3 mainBgColor">
                <input type="text" placeholder="{{ __('lang.job') }}"
                    class="rad14 form-control @error('job') is-invalid @enderror" wire:model="job">
                <button wire:click="addJob" class="btn mainBgColor">
                    {{ __('lang.Apply') }}
                </button>
            </span>
        @endif
        @endif
    </div>

</div>
