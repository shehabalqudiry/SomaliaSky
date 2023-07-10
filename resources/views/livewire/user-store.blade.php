<div class="row p-0">
    <div class="col-12 col-lg-6 my-2">
        <div class="col-12">
            المستخدم
        </div>
        <div class="col-12 pt-3">
            <select class="form-control" wire:change="City()" wire:load="City()" wire:model="country_id" required>
                <option value="" selected>المستخدم</option>
                @foreach($users as $country)
                <option value="{{$country->id}}" @if(old('country_id')==$country->id) selected @endif>{{$country->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    @if ($cities)
    <div class="col-12 col-lg-6 my-2">
        <div class="col-12">
            المدينة
        </div>
        <div class="col-12 pt-3">
            <select class="form-control" wire:model="city_id" name="City" required>
                @foreach($cities as $city)
                <option value="{{$city->id}}" @if(old('City')==$city->id) selected @endif>{{$city->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    @endif
</div>
