<div class="col-12 col-lg-6">
    <div class="row">

        <div class="col-12 col-lg-6 mb-3 mb-lg-0">
            <div class="custom-select2">
                {{-- <span class='spanIcon fas fas fa-map-marker-alt font-2'></span> --}}
                <select wire:change="City()" class="form-control" name="country" wire:model="country_id" style="border-radius: 20px;padding: 10px 40px;color: #1c80ab;">
                    <option value="">{{ __('lang.Country') }}</option>
                    @foreach ($countries as $country)
                        <option {{ request()->country == $country->id ? 'selected' : '' }} value="{{ $country->id }}">
                            {{ $country->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-6 mb-3 mb-lg-0">
            <div class="custom-select2">
                {{-- <span class='spanIcon fas fas fa-map-marker-alt font-2'></span> --}}
                <select name="city" class="form-control" wire:model="city_id" style="border-radius: 20px;padding: 10px 40px;color: #1c80ab;">
                    <option value="">{{ __('lang.Select City') }}</option>
                    @foreach ($cities as $city)
                        <option {{ request()->city == $city->id ? 'selected' : '' }} value="{{ $city->id }}">
                            {{ $city->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
