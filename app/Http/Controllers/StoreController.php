<?php

namespace App\Http\Controllers;

use App\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\{Store, Country, Category, User};

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $stores =  Store::where(function ($q) use ($request) {
            if ($request->id!=null) {
                $q->where('id', $request->id);
            }
            if ($request->q!=null) {
                $q->where('name', 'LIKE', '%'.$request->q.'%')->orWhere('description', 'LIKE', '%'.$request->q.'%');
            }
        })->orderBy('id', 'DESC')->paginate();

        return view('admin.stores.index', compact('stores'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::latest()->get();
        $countries = Country::latest()->get();
        $users = User::latest()->get();
        return view('admin.stores.create', compact('users', 'categories', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge([
            'slug'=>\MainHelper::slug($request->Name)
        ]);

        $validator = Validator::make($request->all(), [
            'Name'              => "required|string|unique:stores,name",
            'Category'          => "required|exists:categories,id",
            'User'              => "required|exists:users,id|unique:stores,user_id",
            'City'              => "required|exists:cities,id",
            'Location'          => "required",
            'Description'       => "nullable|string|max:100000",
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        try {
            $store = Store::create([
                'name'              => $request->Name,
                'user_id'           => $request->User,
                'category_id'       => $request->Category,
                'city_id'           => $request->City,
                'slug'              => $request->slug,
                'location'          => $request->Location,
                'description'       => $request->Description,
                'is_featured'       => $request->is_featured,
                'blocked'           => $request->blocked,
            ]);

            if ($request->hasFile('Avatar')) {
                $file = $this->store_file([
                    'source'=>$request->Avatar,
                    'validation'=>"image",
                    'path_to_save'=>"/uploads/stores/$store->id/",
                    'type'=>'ARTICLE',
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

            if ($request->hasFile('Cover')) {
                $file = $this->store_file([
                    'source'=>$request->Cover,
                    'validation'=>"image",
                    'path_to_save'=>"/uploads/uploads/",
                    'type'=>'ARTICLE',
                    'user_id'=>\Auth::user()->id,
                    'resize'=>[500,1000],
                    'small_path'=>'small/',
                    'visibility'=>'PUBLIC',
                    'file_system_type'=>env('FILESYSTEM_DRIVER'),
                    /*'watermark'=>true,*/
                    'compress'=>'auto'
                ]);
                $store->update(['cover'=>$file['filename']]);
            }
            flash()->success('تم إضافة المتجر بنجاح', 'عملية ناجحة');
            return redirect()->route('admin.stores.index');
        } catch (\Exception $e) {
            flash()->error($e->getMessage(), 'عملية فاشلة');
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {
        $categories = Category::latest()->get();
        $countries = Country::latest()->get();
        $users = User::latest()->get();
        return view('admin.stores.edit', compact('store', 'users', 'categories', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Store $store)
    {
        $request->merge([
            'slug'=>\MainHelper::slug($request->Name)
        ]);

        $validator = Validator::make($request->all(), [
            'Name'              => "required|string|unique:stores,name," . $store->id,
            'Category'          => "required|exists:categories,id",
            'User'              => "required|exists:users,id|unique:stores,user_id," . $store->id,
            'City'              => "required|exists:cities,id",
            'Description'       => "nullable|string|max:100000",
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        try {
            $store->update([
                'name'              => $request->Name,
                'user_id'           => $request->User,
                'category_id'       => $request->Category,
                'city_id'           => $request->City,
                'slug'              => $request->slug,
                'location'          => $request->Location,
                'description'       => $request->Description,
                'is_featured'       => $request->is_featured,
                'blocked'           => $request->blocked,
            ]);

            if ($request->blocked == 1) {
                (new MainHelper())->notify_user([
                    'user_id' => $request->User,
                    'message'=> "تم ايقاف المتجر الخاص بكم الرجاء التواصل مع الادارة",
                    'url'=> route('contact'),
                    'methods'=>['database']
                ]);
            }

            if ($request->hasFile('Avatar')) {
                $file = $this->store_file([
                    'source'=>$request->Avatar,
                    'validation'=>"image",
                    'path_to_save'=>"/uploads/stores/$store->id/avatar/",
                    'type'=>'ARTICLE',
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

            if ($request->hasFile('Cover')) {
                $file = $this->store_file([
                    'source'=>$request->Cover,
                    'validation'=>"image",
                    'path_to_save'=>"/uploads/uploads/",
                    'type'=>'ARTICLE',
                    'user_id'=>\Auth::user()->id,
                    'resize'=>[500,1000],
                    'small_path'=>'small/',
                    'visibility'=>'PUBLIC',
                    'file_system_type'=>env('FILESYSTEM_DRIVER'),
                    /*'watermark'=>true,*/
                    'compress'=>'auto'
                ]);
                $store->update(['cover_image'=>$file['filename']]);
            }
            flash()->success('تم تعديل المتجر بنجاح', 'عملية ناجحة');
            return redirect()->route('admin.stores.index');
        } catch (\Exception $e) {
            flash()->error($e->getMessage(), 'عملية فاشلة');
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        $store->delete();
        flash()->success('تم حذف المتجر بنجاح', 'عملية ناجحة');
        return redirect()->route('admin.stores.index');
    }
}
