<div class="row p-0">
    <div class="col-12 col-lg-6 my-2">
        <div class="col-12">
            {{ __('lang.Country') }}
        </div>
        <div class="col-12 pt-2">
            <select class="form-control rounded-3" wire:change="City()" wire:load="City()" name="Country" wire:model="country_id" required>
                <option value="" selected>{{ __('lang.Country') }}</option>
                @foreach($countries as $country)
                <option value="{{$country->id}}" @if(old('country_id')==$country->id) selected @endif>{{$country->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    @if ($cities)
    <div class="col-12 col-lg-6 my-2">
        <div class="col-12">
            {{ __('lang.City') }}
        </div>
        <div class="col-12 pt-2">
            <select class="form-control rounded-3" wire:model="city_id" name="City" required>
                @foreach($cities as $city)
                <option value="{{$city->id}}" @if(old('City')==$city->id) selected @endif>{{$city->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    @endif
</div>
