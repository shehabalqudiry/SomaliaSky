<div wire:poll>
@if ($store->user)

<p class="profile-description">
    {{-- <i class='font-3 me-2'> {{ __('lang.Rating') }}</i> --}}
    <span class='me-2'> {{ __('lang.Rating') }}</span>
    <span @if(auth()->check()) wire:click="rateF(1)" @else wire:click="$set('message', 1)" @endif
        class="{{ 1 <= $store->user->rate_my->avg('stars') || $store->user->rate_my->avg('stars') >= 2 ? 'fas' : 'far' }} fa-star"
        style="color: rgb(255, 206, 43)"></span>
    <span @if(auth()->check()) wire:click="rateF(2)" @else wire:click="$set('message', 1)" @endif
        class="{{ 2 <= $store->user->rate_my->avg('stars') || $store->user->rate_my->avg('stars') >= 3 ? 'fas' : 'far' }} fa-star"
        style="color: rgb(255, 206, 43)"></span>
    <span @if(auth()->check()) wire:click="rateF(3)" @else wire:click="$set('message', 1)" @endif
        class="{{ 3 <= $store->user->rate_my->avg('stars') || $store->user->rate_my->avg('stars') >= 4 ? 'fas' : 'far' }} fa-star"
        style="color: rgb(255, 206, 43)"></span>
    <span @if(auth()->check()) wire:click="rateF(4)" @else wire:click="$set('message', 1)" @endif
        class="{{ 4 <= $store->user->rate_my->avg('stars') || $store->user->rate_my->avg('stars') >= 5 ? 'fas' : 'far' }} fa-star"
        style="color: rgb(255, 206, 43)"></span>
    <span @if(auth()->check()) wire:click="rateF(5)" @else wire:click="$set('message', 1)" @endif
        class="{{ 5 <= $store->user->rate_my->avg('stars') || $store->user->rate_my->avg('stars') >= 6 ? 'fas' : 'far' }} fa-star"
        style="color: rgb(255, 206, 43)"></span>
    ({{ $store->user->rate_my->count() }})
    @if($message !== 0)
        <span class="badge text-dark bg-transparent p-2">{{ __('lang.Sorry') }}</span>
    @endif

</p>
@else
<p class="profile-description">
    {{-- <i class='font-3 me-2'> {{ __('lang.Rating') }}</i> --}}
    <span class='me-2'> {{ __('lang.Rating') }}</span>
    <span @if(auth()->check()) wire:click="rateF(1)" @else wire:click="$set('message', 1)" @endif
        class="{{ 1 <= $store->rate_my->avg('stars') || $store->rate_my->avg('stars') >= 2 ? 'fas' : 'far' }} fa-star"
        style="color: rgb(255, 206, 43)"></span>
    <span @if(auth()->check()) wire:click="rateF(2)" @else wire:click="$set('message', 1)" @endif
        class="{{ 2 <= $store->rate_my->avg('stars') || $store->rate_my->avg('stars') >= 3 ? 'fas' : 'far' }} fa-star"
        style="color: rgb(255, 206, 43)"></span>
    <span @if(auth()->check()) wire:click="rateF(3)" @else wire:click="$set('message', 1)" @endif
        class="{{ 3 <= $store->rate_my->avg('stars') || $store->rate_my->avg('stars') >= 4 ? 'fas' : 'far' }} fa-star"
        style="color: rgb(255, 206, 43)"></span>
    <span @if(auth()->check()) wire:click="rateF(4)" @else wire:click="$set('message', 1)" @endif
        class="{{ 4 <= $store->rate_my->avg('stars') || $store->rate_my->avg('stars') >= 5 ? 'fas' : 'far' }} fa-star"
        style="color: rgb(255, 206, 43)"></span>
    <span @if(auth()->check()) wire:click="rateF(5)" @else wire:click="$set('message', 1)" @endif
        class="{{ 5 <= $store->rate_my->avg('stars') || $store->rate_my->avg('stars') >= 6 ? 'fas' : 'far' }} fa-star"
        style="color: rgb(255, 206, 43)"></span>
    ({{ $store->rate_my->count() }})
    @if($message !== 0)
        <span class="badge text-dark bg-transparent p-2">{{ __('lang.Sorry, your session has expired.') }}</span>
    @endif

</p>
@endif
</div>
