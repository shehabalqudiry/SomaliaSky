<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(Country::class, 'Country');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $countries =  Country::where(function ($q) use ($request) {
            if ($request->id!=null) {
                $q->where('id', $request->id);
            }
            if ($request->q!=null) {
                $q->where('name'. app()->getLocale(), 'LIKE', '%'.$request->q.'%');
            }
        })->orderBy('id', 'DESC')->paginate();

        return view('admin.countries.index', compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.countries.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $rules = [
            'flag'=>"nullable|image|mimes:png,jpg,svg|max:512",
            'status'=>"required|in:0,1",
        ];
        foreach (config("laravellocalization.supportedLocales") as $key => $lang) {
            // Rules
            $rules["$key.name"] = "required|string|max:255";
            // Lang
            $langAttr["name"][$key] = $data[$key]['name'];
        }
        $request->validate($rules);
        $country = Country::create([
            "name"=>$langAttr["name"],
            "status"=>$request->status,
            "currency_id"=>$request->currency_id,
        ]);
        if ($request->hasFile('flag')) {
            $file = $this->store_file([
                'source'=>$request->flag,
                'validation'=>"image",
                'path_to_save'=>'/uploads/countries/',
                'type'=>'Country',
                'user_id'=>\Auth::user()->id,
                'resize'=>[500,1000],
                'small_path'=>'small/',
                'visibility'=>'PUBLIC',
                'file_system_type'=>env('FILESYSTEM_DRIVER'),
                /*'watermark'=>true,*/
                'compress'=>'auto'
            ]);
            $country->update(['flag'=>$file['filename']]);
        }
        flash()->success('تم إضافة الدوله بنجاح', 'عملية ناجحة');
        return redirect()->route('admin.countries.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Country $country)
    {
        $countries =  Country::where(function ($q) use ($request) {
            if ($request->id!=null) {
                $q->where('id', $request->id);
            }
            if ($request->q!=null) {
                $q->where('name->ar', 'LIKE', '%'.$request->q.'%')->orWhere('description->ar', 'LIKE', '%'.$request->q.'%')->orWhere('name->so', 'LIKE', '%'.$request->q.'%')->orWhere('description->so', 'LIKE', '%'.$request->q.'%')->orWhere('name->en', 'LIKE', '%'.$request->q.'%')->orWhere('description->en', 'LIKE', '%'.$request->q.'%');
            }
        })->orderBy('id', 'DESC')->paginate();

        return view('admin.countries.index', compact('countries'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country)
    {
        return view('admin.countries.edit', compact('country'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Country $country)
    {
        $data = $request->all();
        $rules = [
            'flag'=>"nullable|image|max:512",
            'status'=>"required|in:0,1",
        ];

        foreach (config("laravellocalization.supportedLocales") as $key => $lang) {
            // Rules
            $rules["$key.name"] = "required|string|max:255";
            // Lang
            $langAttr["name"][$key] = $data[$key]['name'];
        }
        $request->validate($rules);


        $country->update([
            "name"=>$langAttr["name"],
            "status"=>$request->status,
            "currency_id"=>$request->currency_id,
        ]);
        if ($request->hasFile('flag')) {
            $file = $this->store_file([
                'source'=>$request->flag,
                'validation'=>"image",
                'path_to_save'=>'/uploads/countries/',
                'type'=>'Country',
                'user_id'=>\Auth::user()->id,
                'resize'=>[500,1000],
                'small_path'=>'small/',
                'visibility'=>'PUBLIC',
                'file_system_type'=>env('FILESYSTEM_DRIVER'),
                /*'watermark'=>true,*/
                'compress'=>'auto'
            ]);
            $country->update(['flag'=>$file['filename']]);
        }

        flash()->success('تم تحديث الدوله بنجاح', 'عملية ناجحة');
        return redirect()->route('admin.countries.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {
        try {
            $country->cities->each(function ($city) {
                $city->states()->delete();
            });
            $country->cities()->delete();
            $country->delete();
            flash()->success('تم حذف الدوله بنجاح', 'عملية ناجحة');
            return redirect()->route('admin.countries.index');
        } catch (\Exception $ex) {
            flash()->error($ex->getMessage(), 'عملية فاشلة');
            return redirect()->route('admin.countries.index');
        }
    }
}
