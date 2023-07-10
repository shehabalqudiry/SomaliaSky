<?php

namespace App\Http\Livewire;

use App\Models\Country;
use Livewire\Component;
use App\Models\City as CityModel;

class CountryCitySelect extends Component
{
    public $countries;
    public $country_id;
    public $cities;
    public $city_id;
    public function __construct($city_id = null)
    {
        $this->countries = Country::latest()->get();
        if ($city_id) {
            $this->city_id = $city_id;
        }
    }

    public function booted()
    {
        // dd($this->category_id);
        $this->cities = CityModel::where('country_id', $this->country_id)->get();
    }

    public function City()
    {
        $this->cities = CityModel::where('country_id', $this->country_id)->get();
    }

    public function render()
    {
        return view('livewire.country-city-select');
    }
}
