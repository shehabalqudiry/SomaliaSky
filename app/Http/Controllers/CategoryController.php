<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\CategoryAttribute;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    // public function __construct()
    // {
    //     $this->authorizeResource(Category::class, 'category');
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories =  Category::where('parent_id', null)->where(function ($q) use ($request) {
            if ($request->id!=null) {
                $q->where('id', $request->id);
            }
            if ($request->q!=null) {
                $q->where('title', 'LIKE', '%'.$request->q.'%')->orWhere('description', 'LIKE', '%'.$request->q.'%');
            }
        })->orderBy('id', 'DESC')->paginate();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categories.create');
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
        // dd($request->all());


        $rules = [];

        foreach (config("laravellocalization.supportedLocales") as $key => $lang) {
            // Rules
            $rules["$key.title"] = "required|string|max:255";
            // Lang
            $langAttr["slug"][$key] = \MainHelper::slug($data[$key]['title']);
            $langAttr["title"][$key] = $data[$key]['title'];
        }
        $request->validate($rules);

        try {
            DB::beginTransaction();

            $category = Category::create([
                "slug" => $langAttr["slug"],
                "title" => $langAttr["title"],
                "parent_id" => $data['parent_id'] ?? null,
            ]);

            if ($request->category_attributes) {
                foreach ($request->category_attributes as $value) {
                    // dd(isset($value['ar.attr']) || ($value['ar.attr'] != null || $value['en.attr'] != null || $value['so.attr'] != null));
                    if (isset($value['ar.attr']) || ($value['ar.attr'] != null || $value['en.attr'] != null || $value['so.attr'] != null)) {
                        $cat_attr = CategoryAttribute::create([
                            "name" => ['ar' => $value['ar.attr'], 'en' => $value['en.attr'], 'so' => $value['so.attr']],
                            "category_id" => $category->id,
                        ]);
                    }
                }
            }

            if ($request->hasFile('image')) {
                $file = $this->store_file([
                    'source'=>$request->image,
                    'validation'=>"image",
                    'path_to_save'=>"/uploads/categories",
                    'type'=>'ARTICLE',
                    'user_id'=>\Auth::user()->id,
                    'resize'=>[500,1000],
                    'small_path'=>'small/',
                    'visibility'=>'PUBLIC',
                    'file_system_type'=>env('FILESYSTEM_DRIVER'),
                    /*'watermark'=>true,*/
                    'compress'=>'auto'
                ]);
                $category->update(['image'=>$file['filename']]);
            }
            DB::commit();
            flash()->success('تم إضافة القسم بنجاح', 'عملية ناجحة');
            return redirect()->route('admin.categories.index');
        } catch (\Exception $e) {
            DB::rollback();
            flash()->success($e->getMessage(), 'عملية فاشلة');
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Category $category)
    {
        $categories =  Category::where('parent_id', $category->id)->where(function ($q) use ($request) {
            if ($request->id!=null) {
                $q->where('id', $request->id);
            }
            if ($request->q!=null) {
                $q->where('title', 'LIKE', '%'.$request->q.'%')->orWhere('description', 'LIKE', '%'.$request->q.'%');
            }
        })->orderBy('id', 'DESC')->paginate();

        return view('admin.categories.sub_index', compact('categories', 'category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $rules = [];
        $data = $request->all();

        foreach (config("laravellocalization.supportedLocales") as $key => $lang) {
            // Rules
            $rules["$key.title"] = "required|string|max:255|unique_translation:categories,title," . $category->id;
            // Lang
            $langAttr["slug"][$key] = \MainHelper::slug($data[$key]['title']);
            $langAttr["title"][$key] = $data[$key]['title'];
        }
        $request->validate($rules);

        try {
            DB::beginTransaction();

            $category->update([
                "slug"=>$langAttr["slug"],
                "title"=>$langAttr["title"],
                "parent_id"=>$request->parent_id,
            ]);

            if ($request->category_attributes) {
                foreach ($request->category_attributes as $key => $value) {
                    if (isset($value['id'])) {
                        $cat_attr = CategoryAttribute::where('id', $value['id'])->first();
                        // dd($cat_attr);
                        if ($cat_attr->getTranslation('name', 'ar') == null || $cat_attr->getTranslation('name', 'en') == null || $cat_attr->getTranslation('name', 'so') == null) {
                            $cat_attr->delete();
                        }
                        $cat_attr->update([
                            "name" => ['ar' => $value['ar.attr'], 'en' => $value['en.attr'], 'so' => $value['so.attr']],
                            "category_id" => $category->id,
                        ]);
                    } elseif (isset($value['ar.attr']) || ($value['ar.attr'] != null || $value['en.attr'] != null || $value['so.attr'] != null)) {
                        CategoryAttribute::create([
                            "name" => ['ar' => $value['ar.attr'], 'en' => $value['en.attr'], 'so' => $value['so.attr']],
                            "category_id" => $category->id,
                        ]);
                    }
                }
            }
            if ($request->hasFile('image')) {
                $file = $this->store_file([
                    'source'=>$request->image,
                    'validation'=>"image",
                    'path_to_save'=>"/uploads/categories",
                    'type'=>'CATEGORIES',
                    'user_id'=>\Auth::user()->id,
                    'resize'=>[500,1000],
                    'small_path'=>'small/',
                    'visibility'=>'PUBLIC',
                    'file_system_type'=>env('FILESYSTEM_DRIVER'),
                    /*'watermark'=>true,*/
                    'compress'=>'auto'
                ]);
                $category->update(['image'=>$file['filename']]);
            }
            DB::commit();
            flash()->success('تم تحديث القسم بنجاح', 'عملية ناجحة');
            return redirect()->route('admin.categories.index');
        } catch (\Exception $e) {
            DB::rollback();
            flash()->success($e->getMessage(), 'عملية فاشلة');
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        flash()->success('تم حذف القسم بنجاح', 'عملية ناجحة');
        return redirect()->route('admin.categories.index');
    }
}
