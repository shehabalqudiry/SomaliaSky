<div>
    <div class="mx-auto mt-0 mb-2 border-2 position-relative">
        @if ($photo)
            <img src="{{ $photo->temporaryUrl() }}" alt="" class="mx-auto border border-5 mainBgColor" style="border-radius: 50%" width="190" height="190">
        @elseif($store)
            <img src="{{ $store->avatar_image() }}" alt="" class="mx-auto border border-5 mainBgColor" style="border-radius: 50%" width="190" height="190">
        @elseif($profile)
            <img src="{{ $profile->getUserAvatar() }}" alt="" class="mx-auto border border-5 mainBgColor" style="border-radius: 50%" width="190" height="190">
        @else
            <img src="{{ env('DEFAULT_IMAGE') }}" alt="" class="mx-auto border border-5 mainBgColor" style="border-radius: 50%" width="190" height="190">
        @endif
        <label for="photo" class="position-absolute mainBgColor rounded-circle p-1 pl-5" style="width: 30px; height : 30px;top: 75%;{{ app()->getLocale() == 'ar' ? 'left : 46.4%' : 'right: 46%' }};">
            <span class="far fa-edit fw-500"></span>
        </label>
    </div>
    <div class="d-block">
        <input type="file" id="photo" hidden name="Avatar" wire:model="photo">
    </div>

    @error('photo') <span class="error">{{ $message }}</span> @enderror

</div>
