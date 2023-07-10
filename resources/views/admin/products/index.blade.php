@extends('layouts.admin')
@section('content')
<div class="col-12 p-3">
	<div class="col-12 col-lg-12 p-0 main-box">

		<div class="col-12 px-0">
			<div class="col-12 p-0 row">
				<div class="col-12 col-lg-4 py-3 px-3">
					<span class="fas fa-products"></span> المنتجات
				</div>
				<div class="col-12 col-lg-4 p-2">
				</div>
				<div class="col-12 col-lg-4 p-2 text-lg-end">
					{{-- @can('create',\App\Models\Product::class) --}}
					<a href="{{route('admin.products.create')}}">
						<span class="btn btn-primary"><span class="fas fa-plus"></span> إضافة جديد</span>
					</a>
					{{-- @endcan --}}
				</div>
			</div>
			<div class="col-12 divider" style="min-height: 2px;"></div>
		</div>

		<div class="col-12 py-2 px-2 row">
			<div class="col-12 col-lg-4 p-2">
				<form method="GET">
					<input type="text" name="q" class="form-control" placeholder="بحث ... ">
				</form>
			</div>
		</div>
		<div class="col-12 p-3" style="overflow:auto">
			<div class="col-12 p-0" style="min-width:1100px;">


			<table class="table table-bordered  table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>العنوان</th>
						<th>القسم</th>
						<th>الرابط</th>
						<th>الشعار</th>
						<th>مميز</th>
						<th>تحكم</th>
					</tr>
				</thead>
				<tbody>
					@foreach($products as $product)
					<tr>
						<td>{{$product->id}}</td>
                        <td>{{$product->title}}</td>
						<td>
							<a href="{{route('admin.categories.index',['id'=>$product->category_id])}}" style="color:#2381c6">{{$product->category->title}}</a>
						</td>
						<td>{{$product->slug}}</td>
						<td><img src="{{$product->main_image()}}" style="width:40px"></td>
						<td>
							@if($product->is_featured==1)
							<span class="fas fa-check-circle text-success" ></span>
							@endif
						</td>
						<td style="width: 270px;">

							@can('view',$product)
							<a href="{{route('product.show',['product'=>$product])}}">
								<span class="btn  btn-outline-primary btn-sm font-1 mx-1">
									<span class="fas fa-search "></span> عرض
								</span>
							</a>
							@endcan

							{{-- @can('update',$product) --}}
							<a href="{{route('admin.products.edit',$product)}}">
								<span class="btn  btn-outline-success btn-sm font-1 mx-1">
									<span class="fas fa-wrench "></span> تحكم
								</span>
							</a>
							{{-- @endcan --}}
							{{-- @can('delete',$product) --}}
							<form method="POST" action="{{route('admin.products.destroy',$product)}}" class="d-inline-block">@csrf @method("DELETE")
								<button class="btn  btn-outline-danger btn-sm font-1 mx-1" onclick="var result = confirm('هل أنت متأكد من عملية الحذف ؟');if(result){}else{event.preventDefault()}">
									<span class="fas fa-trash "></span> حذف
								</button>
							</form>
							{{-- @endcan --}}
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			</div>
		</div>
		<div class="col-12 p-3">
			{{$products->appends(request()->query())->render()}}
		</div>
	</div>
</div>
@endsection
