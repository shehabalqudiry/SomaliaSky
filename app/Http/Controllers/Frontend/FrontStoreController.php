<?php

namespace App\Http\Controllers\Frontend;

use App\Models\City;
use App\Models\Page;
use App\Models\User;
use App\Models\Store;
use App\Models\Article;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Category;
use App\Helpers\MainHelper;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\AnnouncementResource;

class FrontStoreController extends Controller
{
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
        $stores = Store::where('blocked', 0)->where(function ($q) use ($request, $subCats) {
            if ($request->id!=null) {
                $q->where('id', $request->id);
            }
            if ($request->q!=null) {
                $q->where('name', 'LIKE', '%'.$request->q.'%')->orWhere('description', 'LIKE', '%'.$request->q.'%');
            }

            if ($request->city!=null) {
                $q->where('city_id', $request->city);
            }

            if ($request->country!=null) {
                $q->whereHas('city', function ($qs) use ($request) {
                    return $qs->where('country_id', '=', $request->country);
                })->get();
            }

            if ($request->category_id) {
                $q->where('category_id', $request->category_id);
            }

            if ($request->Category!=null) {
                $q->whereHas('category', function ($qs) use ($request) {
                    return $qs->where('parent_id', '=', $request->Category)->orWhere('id', $request->Category);
                });
            }
        })->latest();

        try {
            $city = get_user_country($request);
        } catch (\Exception $e) {
            $city = ['city'=>null];
        }

        if ($request->user() && (!$request->country || !$request->city)) {
            $stores = $stores->where('city_id', $request->user()->city_id);
        } elseif (!$request->country && !$request->city) {
            $ci = $city != false ? $city['city'] : 'n';
            $cityTrans = trans("lang." . $ci);
            $city = City::where('name', 'LIKE', "%" . $ci . "%")->orWhere('name', 'LIKE', "%" . $cityTrans . "%")->first();
            $stores->where('city_id', $city->id ?? 0);
        }

        $stores = $stores->paginate();
        return view('front.stores.index', compact('stores', 'categories'));
    }

    public function my_store(Request $request)
    {
        $store = auth()->user()->store;
        if (!$store) {
            return view('front.stores.open-store');
        }
        return view('front.stores.my-store', compact('store'));
    }

    public function user_show(Request $request, User $user)
    {
        return view('front.user.show', compact('user'));
    }

    public function show(Request $request, Store $store)
    {
        return view('front.stores.show', compact('store'));
    }

    public function my_store_edit(Request $request)
    {
        $store = auth()->user()->store;
        return view('front.stores.edit-store', compact('store'));
    }

    public function my_store_save(Request $request)
    {
        $request->merge([
            'slug'=>\MainHelper::slug($request->Name)
        ]);

        $validator = Validator::make($request->all(), [
            'Name'              => "required|string|unique:stores,name",
            'Category'          => "required|exists:categories,id",
            'City'              => "required|exists:cities,id",
            'Description'       => "nullable|max:100000",
            'Avatar'            => "image|mimes:png,jpg,jpeg|max:2048",
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        try {
            $store = Store::create([
                'name'              => $request->Name,
                'user_id'           => auth()->user()->id,
                'category_id'       => $request->Category,
                'city_id'           => $request->City,
                'slug'              => $request->slug,
                'location'          => $request->Location,
                'description'       => $request->Description,
                'blocked'           => 2,
            ]);

            if ($request->hasFile('Avatar')) {
                $file = $this->store_file([
                    'source'=>$request->Avatar,
                    'validation'=>"image",
                    'path_to_save'=>"/uploads/stores/$store->id",
                    'type'=>'stores',
                    'user_id'=>\Auth::user()->id,
                    'resize'=>[500,1000],
                    'small_path'=>'small/',
                    'visibility'=>'PUBLIC',
                    'file_system_type'=>env('FILESYSTEM_DRIVER'),
                    /*'watermark'=>true,*/
                    'compress'=>'auto'
                ]);
                $store->update(['avatar'=>$file['filename']]);
            }

            if ($request["fileuploader-list-file"]) {
                $uploaded_files=json_decode($request["fileuploader-list-file"]);
                if (count($uploaded_files)) {
                    $store->update(['cover_image'=>$uploaded_files[0]->file]);
                }
            }

            (new MainHelper())->notify_user([
                'user_id' => 1,
                'message'=> "طلب فتح متجر جديد : $store->title \n للتاجر : " . $request->user()->name ,
                'url'=> route('admin.stores.index', ['id' => $store->id]),
                'methods'=>['database']
            ]);

            return redirect()->route('front.store.my_store')->with('success', 'Store Created Successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function my_store_update(Request $request)
    {
        $store = auth()->user()->store;
        $request->merge([
            'slug'=>\MainHelper::slug($request->Name)
        ]);

        $validator = Validator::make($request->all(), [
            'Name'              => "required|string|unique:stores,name," . $store->id,
            'Category'          => "required|exists:categories,id",
            'City'              => "required|exists:cities,id",
            'Description'       => "nullable|max:100000",
            'Avatar'            => "image|mimes:png,jpg,jpeg|max:2048",
        ]);
        if ($validator->fails()) {
            return $this->returnError(404, $validator->errors()->first());
        }
        try {
            $store->update([
                'name'              => $request->Name,
                'category_id'       => $request->Category,
                'city_id'           => $request->City,
                'slug'              => $request->slug,
                'location'          => $request->Location,
                'description'       => $request->Description,
            ]);

            if ($request->hasFile('Avatar')) {
                $file = $this->store_file([
                    'source'=>$request->Avatar,
                    'validation'=>"image",
                    'path_to_save'=>"/uploads/stores/$store->id",
                    'type'=>'stores',
                    'user_id'=>\Auth::user()->id,
                    'resize'=>[500,1000],
                    'small_path'=>'small/',
                    'visibility'=>'PUBLIC',
                    'file_system_type'=>env('FILESYSTEM_DRIVER'),
                    /*'watermark'=>true,*/
                    'compress'=>'auto'
                ]);
                // $this->remove_hub_file($store->avatar);
                $store->update(['avatar'=>$file['filename']]);
            }

            if ($request["fileuploader-list-file"]) {
                $uploaded_files=json_decode($request["fileuploader-list-file"]);
                if (count($uploaded_files)) {
                    $this->remove_hub_file($store->cover_image);
                    $store->update(['cover_image'=>$uploaded_files[0]->file]);
                }
            }


            return redirect()->route('front.store.my_store')->with('success', 'Store Created Successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    public function create_announcement(Request $request)
    {
        return view('front.announcements.create');
    }

    public function store_announcement(Request $request)
    {
    }
}
