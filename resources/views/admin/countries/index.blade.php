@extends('layouts.admin')

@section('content')
<div class="col-12 p-3">
	<div class="col-12 col-lg-12 p-0 main-box">

		<div class="col-12 px-0">
			<div class="col-12 p-0 row">
				<div class="col-12 col-lg-4 py-3 px-3">
					<span class="fas fa-countries"></span> الدول
				</div>
				<div class="col-12 col-lg-4 p-2">
				</div>
				<div class="col-12 col-lg-4 p-2 text-lg-end">
					{{-- @can('create',\App\Models\Country::class) --}}
					<a href="{{route('admin.countries.create')}}">
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
						<th>الدوله</th>
						<th>العملة</th>
						<th>الشعار</th>
						<th>تحكم</th>
					</tr>
				</thead>
				<tbody>
					@foreach($countries as $country)
					<tr>
						<td>{{$country->id}}</td>

						<td>{{$country->name}}</td>
						<td>{{$country->currency->name ?? ''}}</td>
						<td><img src="{{$country->image_path}}" style="width:40px"></td>

						<td>
							{{-- @can('update',$country) --}}
							<a href="{{route('admin.countries.edit',$country)}}">
							<span class="btn  btn-outline-success btn-sm font-1 mx-1">
								<span class="fas fa-wrench "></span> تحكم
							</span>
							</a>

                            <a href="{{route('admin.cities.index',['country_id' => $country->id])}}">
							<span class="btn  btn-outline-secondary btn-sm font-1 mx-1">
								<span class="fas fa-flag"></span> المدن
							</span>
							</a>
							{{-- @endcan
							@can('delete',$country) --}}
							<form method="POST" action="{{route('admin.countries.destroy',$country)}}" class="d-inline-block">@csrf @method("DELETE")
								<button class="btn btn-outline-danger btn-sm font-1 mx-1" onclick="var result = confirm('هل أنت متأكد من عملية الحذف ؟');if(result){}else{event.preventDefault()}">
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
			{{$countries->appends(request()->query())->render()}}
		</div>
	</div>
</div>
@endsection
