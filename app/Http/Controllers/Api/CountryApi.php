<?php

namespace App\Http\Controllers\Api;

use App\Models\Currency;
use App\Traits\GeneralTrait;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Models\{City, State, Country};
use App\Http\Resources\{CityResource, StateResource, CountryResource};

class CountryApi extends Controller
{
    use GeneralTrait;

    public function get_all_countries(Request $request)
    {
        $countries = CountryResource::collection(Country::get());
        if ($request->name) {
            $countries = CountryResource::collection(Country::where('name', "LIKE", "%$request->name%")->get());
        }
        return $this->returnData("data", $countries, "All Countries");
    }

    public function get_all_currencies(Request $request)
    {
        $currencies = Currency::get();
        if ($request->name) {
            $currencies = Currency::where('name', "LIKE", "%$request->name%")->get();
        }
        return $this->returnData("data", $currencies, "All currencies");
    }

    public function get_country_by_name(Request $request, $country_name = null)
    {
        $realIP = $request->ip();
        $url = "http://api.ipapi.com/$realIP?access_key=0c2cc3a014f062d25fa953da376b43a1";
        $country = Http::get($url);

        $country_name = trans("lang." . $country['country_name']);
        $data = Country::where('name', "LIKE", "%$country_name%")->orWhere('name', "LIKE", "%" . $country['country_name'] . "%")->with('cities')->first();
        if ($data) {
            $data->CountryCities = true;
            $data->flagIcon = $country['location']['country_flag_emoji'];
            $country = new CountryResource($data);
            return $this->returnData("data", $country, "User Country");
        }
        return $this->returnError(404, "Country Not Found");
    }

    public function get_all_cities($country_id)
    {
        if (!$country_id) {
            return $this->returnError(false, 404, "country id is required");
        }
        $cities = CityResource::collection(City::where("country_id", $country_id)->get());
        return $this->returnData("data", $cities, "All Cities");
    }

    public function get_all_states($city_id)
    {
        if (!$city_id) {
            return $this->returnError(false, 404, "city id is required");
        }
        $states = StateResource::collection(State::where("city_id", $city_id)->get());
        return $this->returnData("data", $states, "All States");
    }
}
