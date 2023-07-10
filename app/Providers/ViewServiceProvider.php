<?php

namespace App\Providers;

use App\Models\City;
use App\Models\Country;
use App\Models\Category;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        view()->composer('layouts.inc.search',function($view){
            $country = get_user_country(request());
            $cities = City::orderBy('name', 'DESC')->get();
            if ($country) {
                $country = Country::where('name', 'LIKE', "%" . $country['country_name'] ?? null . "%")->first();
                $cities = $country ? $country->cities : [];
            } else {
                $cities = City::orderBy('name', 'DESC')->get();
                if (auth()->check() && auth()->user()->city) {
                    // dd(auth()->user());
                    $cities = $cities->where('country_id', auth()->user()->city->country_id);
                }
            }
            $categories = Category::latest()->get();
            $view->with([
                'categories' => $categories,
                'cities'    => $cities,
            ]);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
