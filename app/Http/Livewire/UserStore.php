<?php

namespace App\Http\Livewire;

use Livewire\Component;

class UserStore extends Component
{
    public $users, $country_id, $cities, $city_id;
    public function __construct($city_id = null) {
        $this->users = Country::latest()->get();
        if ($city_id) {
            $this->city_id = $city_id;
        }
    }

    public function booted()
    {
        // dd($this->category_id);
        $this->cities = CityModel::where('country_id', $this->country_id)->get();
    }

    public function City() {
        $this->cities = CityModel::where('country_id', $this->country_id)->get();
    }

    public function render()
    {
        return view('livewire.user-store');
    }
}
