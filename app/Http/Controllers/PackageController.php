<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(Package::class, 'package');
    }
    public function index(Request $request)
    {
        $packages =  Package::where(function ($q) use ($request) {
            if ($request->id!=null) {
                $q->where('id', $request->id);
            }
            if ($request->q!=null) {
                $q->where('title', 'LIKE', '%'.$request->q.'%')->orWhere('description', 'LIKE', '%'.$request->q.'%');
            }
        })->orderBy('id', 'DESC')->paginate();

        return view('admin.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.packages.create');
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
            'price'                 => "required|numeric",
            'announcement_number'   => "numeric",
            'time'                  => "numeric",
        ];

        foreach (config("laravellocalization.supportedLocales") as $key => $lang) {
            // Rules
            $rules["$key.title"] = "required|string|max:255";
            $rules["$key.description"] = "string|max:255";
            // Lang
            $langAttr["title"][$key] = $data[$key]['title'];
            $langAttr["description"][$key] = $data[$key]['description'];
        }
        $request->validate($rules);


        $package = Package::create([
            'title'                 => $langAttr["title"],
            'description'           => $langAttr["description"],
            'price'                 => $request->price,
            'announcement_number'   => $request->announcement_number,
            'time'                  => $request->time,
        ]);

        flash()->success('تم بنجاح', 'عملية ناجحة');
        return redirect()->route('admin.packages.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\package  $package
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Package $package)
    {
        $packages =  Package::where(function ($q) use ($request) {
            if ($request->id!=null) {
                $q->where('id', $request->id);
            }
            if ($request->q!=null) {
                $q->where('title', 'LIKE', '%'.$request->q.'%')->orWhere('description', 'LIKE', '%'.$request->q.'%');
            }
        })->orderBy('id', 'DESC')->paginate();

        return view('admin.packages.index', compact('packages'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\package  $package
     * @return \Illuminate\Http\Response
     */
    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\package  $package
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Package $package)
    {
        // dd($request->all());
        $data = $request->all();
        $rules = [
            'price'                 => "required|numeric",
            'announcement_number'   => "numeric",
            'time'                  => "numeric",
        ];

        foreach (config("laravellocalization.supportedLocales") as $key => $lang) {
            // Rules
            $rules["$key.title"] = "required|string|max:255";
            $rules["$key.description"] = "string|max:255";
            // Lang
            $langAttr["title"][$key] = $data[$key]['title'];
            $langAttr["description"][$key] = $data[$key]['description'];
        }
        // dd($langAttr);
        $request->validate($rules);


        $package->update([
            'title'                 => $langAttr["title"],
            'description'           => $langAttr["description"],
            'price'                 => $request->price,
            'announcement_number'   => $request->announcement_number,
            'time'                  => $request->time,
        ]);

        flash()->success('تم تحديث بنجاح', 'عملية ناجحة');
        return redirect()->route('admin.packages.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\package  $package
     * @return \Illuminate\Http\Response
     */
    public function destroy(Package $package)
    {
        try {
            $package->delete();
            flash()->success('تم حذف بنجاح', 'عملية ناجحة');
            return redirect()->route('admin.packages.index');
        } catch (\Exception $ex) {
            flash()->error($ex->getMessage(), 'عملية فاشلة');
            return redirect()->route('admin.packages.index');
        }
    }
}
