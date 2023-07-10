<?php

namespace App\Http\Controllers\Frontend;

use App\Models\City;
use App\Models\Page;
use App\Models\Article;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Category;
use App\Helpers\MainHelper;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Http\Resources\AnnouncementResource;

class FrontCategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('parent_id', null)->where(function ($q) use ($request) {
            if ($request->q!=null) {
                $q->where('title', 'LIKE', '%'.$request->q.'%')->orWhere('description', 'LIKE', '%'.$request->q.'%');
            }
        })->latest()->get();
        return view('front.categories.index', compact('categories'));
    }
    public function contact_post(Request $request)
    {
        $request->validate([
            'name'=>"required|min:3|max:190",
            'email'=>"nullable|email",
            "phone"=>"required|numeric",
            "message"=>"required|min:3|max:10000",
        ]);
        // if(\MainHelper::recaptcha($request->recaptcha)<0.8)abort(401);
        Contact::create([
            'user_id'=>auth()->check() ? auth()->id() : null,
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'message'=>/*"قادم من : ".urldecode(url()->previous())."\n\nالرسالة : ".*/$request->message
        ]);

        flash()->success('تم استلام رسالتك بنجاح وسنتواصل معك في أقرب وقت');
        //\Session::flash('message', __("Your Message Has Been Send Successfully And We Will Contact You Soon !"));
        return redirect()->back();
    }
    public function show(Request $request, Category $category)
    {
        $subCats = [];
        $subcats = [];
        $catM = Category::where('id', $category)->first();
        if (!$catM) {
            $catM = $category;
        }

        if ($catM) {
            foreach ($catM->subCats as $cat) {
                $subCats[] = $cat->id;
                $subcats[] = $cat;
            }
        }
        $announcements = Announcement::where('status', '!=', 0)->where(function ($q) use ($request, $subCats, $catM, $category) {
            if (!$request->Category) {
                $q->where('category_id', $category)->orWhereHas('category', function ($qs) use ($request, $catM) {
                    return $qs->where('parent_id', '=', $catM->id);
                });
            }

            if ($request->id!=null) {
                $q->where('id', $request->id);
            }
            if ($request->q!=null) {
                $q->where('title->'.app()->getLocale(), 'LIKE', '%'.$request->q.'%')->orWhere('description->'.app()->getLocale(), 'LIKE', '%'.$request->q.'%');
            }

            if ($request->from!=null || $request->to != null) {
                $q->whereBetween('price', [$request->from, $request->to]);
            }

            if ($request->country!=null) {
                $q->whereHas('city', function ($qs) use ($request) {
                    return $qs->where('country_id', '=', $request->country);
                })->get();
            }

            if ($request->category_id!=null) {
                $q->where('category_id', $request->category_id);
            }

            if ($request->Category!=null) {
                $q->where('category_id', $request->Category);
            }

            if ($request->city!=null) {
                $q->where('city_id', $request->city);
            }

            if ($request->is_featured!=null) {
                $q->where('is_featured', '!=', 0);
            }

            if ($request->with_photos!=null) {
                $q->where('images', '!=', null);
            }

            if ($request->with_price!=null) {
                $q->where('price', '!=', 0);
            }
        })->orderBy('is_featured', 'DESC');

        try {
            $city = get_user_country($request);
        } catch (\Exception $e) {
            $city = ['city'=>null];
        }

        if ($request->user() && (!$request->country and !$request->city)) {
            $announcements = $announcements->where('city_id', $request->user()->city_id);
        } elseif (!$request->country and !$request->city) {
            $ci = $city != false ? $city['city'] : 'n';
            $cityTrans = trans("lang." . $ci);
            $city = City::where('name', 'LIKE', "%" . $ci . "%")->orWhere('name', 'LIKE', "%" . $cityTrans . "%")->first();
            $announcements->where('city_id', $city->id ?? 1);
        }

        $announcements = $announcements->paginate();

        return view('front.categories.show', compact('subcats', 'category', 'announcements'));
    }
}
