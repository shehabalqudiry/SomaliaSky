<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index(Request $request)
    {
        $sliders =  Slider::where(function ($q) use ($request) {
            if ($request->id!=null) {
                $q->where('id', $request->id);
            }
            if ($request->q!=null) {
                $q->where('link', 'LIKE', '%'.$request->q.'%');
            }
        })->orderBy('id', 'DESC')->paginate();

        return view('admin.sliders.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.sliders.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'link'=>"required|url",
        ]);
        try {
            $slider = Slider::create([
                "link"=>$request->link,
            ]);


            if ($request->hasFile('image')) {
                $file = $this->store_file([
                    'source'=>$request->image,
                    'validation'=>"image",
                    'path_to_save'=>"/uploads/sliders",
                    'type'=>'SLIDER',
                    'user_id'=>\Auth::user()->id,
                    'resize'=>[500,1000],
                    'small_path'=>'small/',
                    'visibility'=>'PUBLIC',
                    'file_system_type'=>env('FILESYSTEM_DRIVER'),
                    /*'watermark'=>true,*/
                    'compress'=>'auto'
                ]);
                $slider->update(['image'=>$file['filename']]);
            }
            flash()->success('تم إضافة الاعلان في الرئيسية بنجاح', 'عملية ناجحة');
            return redirect()->route('admin.sliders.index');
        } catch (\Exception $e) {
            flash()->success($e->getMessage(), 'عملية فاشلة');
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'link'=>"required|url",
        ]);
        try {
            $slider->update([
                "link"=>$request->link,
            ]);

            if ($request->hasFile('image')) {
                $file = $this->store_file([
                    'source'=>$request->image,
                    'validation'=>"image",
                    'path_to_save'=>"/uploads/sliders",
                    'type'=>'SLIDERS',
                    'user_id'=>\Auth::user()->id,
                    'resize'=>[500,1000],
                    'small_path'=>'small/',
                    'visibility'=>'PUBLIC',
                    'file_system_type'=>env('FILESYSTEM_DRIVER'),
                    /*'watermark'=>true,*/
                    'compress'=>'auto'
                ]);
                $slider->update(['image'=>$file['filename']]);
            }
            flash()->success('تم تحديث الاعلان في الرئيسية بنجاح', 'عملية ناجحة');
            return redirect()->route('admin.sliders.index');
        } catch (\Exception $e) {
            flash()->success($e->getMessage(), 'عملية فاشلة');
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slider $slider)
    {
        $slider->delete();
        flash()->success('تم حذف الاعلان في الرئيسية بنجاح', 'عملية ناجحة');
        return redirect()->route('admin.sliders.index');
    }
}
