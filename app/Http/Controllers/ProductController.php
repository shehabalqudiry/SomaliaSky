<?php

namespace App\Http\Controllers;

// use App\Http\Requests\StoreProductRequest;
// use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;


class ProductController extends Controller
{


    public function index(Request $request)
    {
        $products =  Product::where(function($q)use($request){
            if($request->id!=null)
                $q->where('id',$request->id);
            if($request->q!=null)
                $q->where('title','LIKE','%'.$request->q.'%')->orWhere('description','LIKE','%'.$request->q.'%');
        })->orderBy('id','DESC')->paginate();

        return view('admin.products.index',compact('products'));
    }

    public function create()
    {
        $categories= Category::orderBy('id','DESC')->get();
        return view('admin.products.create',compact('categories'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'slug'=>\MainHelper::slug($request->title)
        ]);

        $request->validate([
            'slug'=>"required|max:190|unique:products,slug",
            'category_id'=>"required|exists:categories,id",
            'is_featured'=>"required|in:0,1",
            'title'=>"required|max:190",
            'price'=>"required",
            'description'=>"nullable|max:100000",
            'meta_description'=>"nullable|max:10000",
        ]);
        $product = Product::create([
            "slug"=>$request->slug,
            "price"=>$request->price,
            "category_id"=>$request->category_id,
            "is_featured"=>$request->is_featured==1?1:0,
            "title"=>$request->title,
            "description"=>$request->description,
            "meta_description"=>$request->meta_description,
        ]);

        if($request->hasFile('main_image')){
            $file = $this->store_file([
                'source'=>$request->main_image,
                'validation'=>"image",
                'path_to_save'=>'/uploads/products/',
                'type'=>'PRODUCT',
                'user_id'=>\Auth::user()->id,
                'resize'=>[500,1000],
                'small_path'=>'small/',
                'visibility'=>'PUBLIC',
                'file_system_type'=>env('FILESYSTEM_DRIVER'),
                /*'watermark'=>true,*/
                'compress'=>'auto'
            ]);
            $product->update(['main_image'=>$file['filename']]);
        }
        flash()->success('تم إضافة المنتج بنجاح','عملية ناجحة');
        return redirect()->route('admin.products.index');
    }


    public function show(Product $product)
    {

    }


    public function edit(Product $product)
    {
        $categories= Category::orderBy('id','DESC')->get();
        return view('admin.products.edit',compact('product','categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->merge([
            'slug'=>\MainHelper::slug($request->title)
        ]);

        $request->validate([
            'slug'=>"required|max:190|unique:products,slug,".$product->id,
            'category_id'=>"required|exists:categories,id",
            'is_featured'=>"required|in:0,1",
            'title'=>"required|max:190",
            'price'=>"required",
            'description'=>"nullable|max:100000",
            'meta_description'=>"nullable|max:10000",
        ]);
        $product->update([
            'category_id'=>$request->category_id,
            "slug"=>$request->slug,
            "price"=>$request->price,
            "is_featured"=>$request->is_featured==1?1:0,
            "title"=>$request->title,
            "description"=>$request->description,
            "meta_description"=>$request->meta_description,
        ]);
        $product->categories()->sync($request->category_id);
        if($request->hasFile('main_image')){
            $file = $this->store_file([
                'source'=>$request->main_image,
                'validation'=>"image",
                'path_to_save'=>'/uploads/products/',
                'type'=>'PRODUCT',
                'user_id'=>\Auth::user()->id,
                'resize'=>[500,1000],
                'small_path'=>'small/',
                'visibility'=>'PUBLIC',
                'file_system_type'=>env('FILESYSTEM_DRIVER'),
                /*'watermark'=>true,*/
                'compress'=>'auto'
            ]);
            $product->update(['main_image'=>$file['filename']]);
        }
        flash()->success('تم تحديث المنتج بنجاح','عملية ناجحة');
        return redirect()->route('admin.products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        flash()->success('تم حذف المنتج بنجاح','عملية ناجحة');
        return redirect()->route('admin.products.index');
    }
}
