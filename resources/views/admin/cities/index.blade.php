@extends('layouts.admin')

@section('content')
<div class="col-12 p-3">
	<div class="col-12 col-lg-12 p-0 main-box">

		<div class="col-12 px-0">
			<div class="col-12 p-0 row">
				<div class="col-12 col-lg-4 py-3 px-3">
					<span class="fas fa-flag"></span> المدن في دولة <span class="fas fa-arrow-left"></span> {{ \App\Models\Country::where('id', request()->country_id)->first()->name ?? "" }}
				</div>
				<div class="col-12 col-lg-4 p-2">
				</div>
				<div class="col-12 col-lg-4 p-2 text-lg-end">
					{{-- @can('create',\App\Models\City::class) --}}
					<a href="{{route('admin.cities.create', ["country_id" => request()->country_id])}}">
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
						<th>المدينة</th>
						<th>تحكم</th>
					</tr>
				</thead>
				<tbody>
					@foreach($cities as $city)
					<tr>
						<td>{{$city->id}}</td>

						<td>{{$city->name}}</td>
						<td>
							{{-- @can('update',$city) --}}
							<a href="{{route('admin.cities.edit',$city)}}">
							<span class="btn  btn-outline-success btn-sm font-1 mx-1">
								<span class="fas fa-wrench "></span> تحكم
							</span>
							</a>

                            <a href="{{route('admin.states.index',['city_id' => $city->id])}}">
							<span class="btn  btn-outline-secondary btn-sm font-1 mx-1">
								<span class="fas fa-flag"></span> (مناطق/محافظات/احياء)
							</span>
							</a>
							{{-- @endcan
							@can('delete',$city) --}}
							<form method="POST" action="{{route('admin.cities.destroy',$city)}}" class="d-inline-block">@csrf @method("DELETE")
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
			{{$cities->appends(request()->query())->render()}}
		</div>
	</div>
</div>
@endsection
