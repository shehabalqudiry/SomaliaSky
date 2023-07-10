<?php

namespace App\Http\Controllers\Api;

use App\Models\City;
use App\Models\Slider;

use App\Models\Setting;
use App\Models\{Category};
use App\Models\Announcement;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AnnouncementResource;
use App\Http\Resources\{CityResource, StateResource, CategoryResource};

class HomeApi extends Controller
{
    use GeneralTrait;
    public function index(Request $request)
    {
        $sliders = Slider::latest()->get();
        $categories = CategoryResource::collection(Category::where('parent_id', null)->latest()->get());
        $is_featured = Announcement::whereRelation('store', 'blocked', 0)->where([['is_featured', '!=', 0], ['status', '!=', 0]])->orderBy('is_featured', 'DESC')->get();
        $announcements = Announcement::whereRelation('store', 'blocked', 0)->where([['is_featured', 0], ['status', '!=', 0]])->latest()->get();
        try {
            $city = get_user_country($request);
        } catch (\Exception $e) {
            $city = ['city'=>null];
        }

        if ($request->user()) {
            $is_featured = $is_featured->where('city_id', $request->user()->city_id);
            $announcements = $announcements->where('city_id', $request->user()->city_id);
        } else {
            $ci = $city != false ? $city['city'] : 'n';
            $cityTrans = trans("lang." . $ci);
            $city = City::where('name', 'LIKE', "%" . $ci . "%")->orWhere('name', 'LIKE', "%" . $cityTrans . "%")->first();
            $announcements->where('city_id', $city->id ?? 0);
        }

        if ($request->category_id) {
            $is_featured = $is_featured->where('category_id', $request->category_id);
            $announcements = $announcements->where('category_id', $request->category_id);
        }

        if (!count($is_featured)) {
            $is_featured = Announcement::whereRelation('store', 'blocked', 0)->where([['is_featured', '!=', 0], ['status', '!=', 0]])->orderBy('is_featured', 'DESC')->get();
        }

        if (!count($announcements)) {
            $announcements = Announcement::whereRelation('store', 'blocked', 0)->where([['is_featured', 0], ['status', '!=', 0]])->latest()->get();
        }

        $data = [
            'sliders' => $sliders,
            'categories' => $categories->take(6),
            'is_featured' => AnnouncementResource::collection($is_featured->take(2)),
            'announcements' => AnnouncementResource::collection($announcements->take(4)),
        ];
        return $this->returnData("data", $data, "Home");
    }

    public function settings()
    {
        $settings = Setting::first();
        $settings['website_logo'] = $settings->website_logo();
        $settings['website_wide_logo'] = $settings->website_wide_logo();
        $settings['website_icon'] = $settings->website_icon();
        $settings['website_cover'] = $settings->website_cover();
        // $settings['about'] = $settings->website_cover();
        return $this->returnData("data", $settings, "settings");
    }
}
