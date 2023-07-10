<?php

namespace App\Http\Controllers\Frontend;

use App\Models\City;
use App\Models\User;
use App\Models\Package;
use App\Models\Category;
use App\Helpers\MainHelper;
use App\Models\Announcement;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\AnnouncementAttribute;
use Illuminate\Support\Facades\Validator;

class FrontAnnouncementController extends Controller
{
    use GeneralTrait;

    public function index(Request $request)
    {
        $categories = [];
        $subCats = [];
        $category = Category::where('id', $request->Category)->first();
        if ($category) {
            foreach ($category->subCats as $cat) {
                $subCats[] = $cat->id;
            }
        }
        $announcements = Announcement::where('status', '!=', 0)->where(function ($q) use ($request) {
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

            if ($request->Category!=null) {
                $q->whereHas('category', function ($qs) use ($request) {
                    return $qs->where('parent_id', '=', $request->Category)->orWhere('id', $request->Category);
                });
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
        return view('front.announcements.index', compact('announcements', 'categories'));
    }

    public function create()
    {
        return view('front.announcements.create');
    }

    public function createStoreAnnouncement()
    {
        return view('front.announcements.createStoreAnnouncement');
    }


    public function store(Request $request)
    {
        $data = $request->all();
        // dd($request->all());

        $rules = [
            'Category'=>"required|exists:categories,id",
            'City'=>"required|exists:cities,id",
            'price'=>"required|numeric",
        ];

        foreach (config("laravellocalization.supportedLocales") as $key => $lang) {
            // Rules
            // $rules["$key.title"] = "string|max:255";
            // Lang
            $langAttr["title"][$key] = $data[$key]['title'] ?? $data[app()->getLocale()]['title'];
            $langAttr["Description"][$key] = $data[$key]['Description'] ?? $data[app()->getLocale()]['Description'];
        }
        $request->validate($rules);

        // if ($validator->fails()) {
        //     return back()->withErrors($validator)->withInput();
        // }
        $uploaded_files=json_decode($request["fileuploader-list-file"]);

        $sub = $request->user()->subscription->where('status', 1)->first();
        if ($sub && $sub->package->announcement_number - $request->user()->announcements->count() < 1) {
            flash()->error(__('lang.limited'), __('lang.There was a problem executing the action.'));
            return back();
        }

        try {
            if (!Category::where(['id'=> $request->Category, ['parent_id', "!=", null]])->first()) {
                flash()->error(__('limited'), __('lang.There was a problem executing the action.'));
                return back()->with("error", 'القسم ليس قسم فرعي او انه غير موجود');
            }

            DB::beginTransaction();
            $user = auth()->user();
            $announcement = Announcement::create([
                "user_id"           => $user->id,
                "store_id"          => $user->store ? $user->store->id : $user->id,
                "category_id"       => $request->Category,
                "currency_id"       => $request->currency,
                "type"              => $request->type,
                "city_id"           => $request->City,
                "title"             => $langAttr["title"],
                "description"       => $langAttr["Description"],
                "is_featured"       => 0,
                "number"            => $this->generateAnnouncementNumber(),
                "price"             => $request->price,
                "status"            => 0,
            ]);

            if ($request->announcement_attributes) {
                // dd($request->announcement_attributes);
                foreach ($request->announcement_attributes as $value) {
                    $announcement_attr = AnnouncementAttribute::create([
                        "name" => ['ar' => $value['ar.attr'], 'en' => $value['en.attr'], 'so' => $value['so.attr']],
                        "value" => ['ar' => $value['ar.value'], 'en' => $value['en.value'], 'so' => $value['so.value']],
                        "announcement_id" => $announcement->id,
                    ]);
                }
            }

            if (count($uploaded_files)) {
                $attachments=[];
                foreach ($uploaded_files as $uploaded_file) {
                    array_push($attachments, $uploaded_file->file);
                }
                $attachments = implode(',', $attachments);
                $announcement->update(['images' => $attachments]);
            }


            // try {
            (new MainHelper())->notify_user([
                'user_id' => 1,
                'message'=> "تم اضافة اعلان جديد في انتظار الموافقة : $announcement->title \n للتاجر : " . $request->user()->name ,
                'url'=> route('admin.announcements.index', ['id' => $announcement->id]),
                'methods'=>['database']
            ]);
            // } catch (\Throwable $th) {
            // }

            DB::commit();
            flash()->success(__('lang.The :resource was created!', ['resource' => $announcement->title]), __('lang.The action ran successfully!'));
            return redirect()->route('front.store.announcement_vip', $announcement);
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('lang.Server Error'), __('lang.No Results Found.'));
            return back()->with("error", __('lang.Server Error'));
        }
    }


    public function show(Announcement $announcement)
    {
        $shareButtons = \Share::page(
            route('front.announcements.show', $announcement),
            "$announcement->title",
        )->facebook()
        ->twitter()
        ->telegram()
        ->whatsapp()
        ->getRawLinks();
        $relatedAds = Announcement::where('category_id', $announcement->category_id)->take(6)->latest()->get();
        // $relatedAds = '';
        return view('front.announcements.show', compact('announcement', 'relatedAds', 'shareButtons'));
    }


    public function edit(Announcement $announcement)
    {
        //
    }


    public function update(Request $request, Announcement $announcement)
    {
        //
    }

    public function destroy(Announcement $announcement)
    {
        if ($announcement->AnnouncementAttribute) {
            foreach ($announcement->AnnouncementAttribute as $att) {
                $att->delete();
            }
        }
        $announcement->delete();
        flash()->success(__('lang.Delete'), __('lang.The action ran successfully!'));
        return back();
    }
}
