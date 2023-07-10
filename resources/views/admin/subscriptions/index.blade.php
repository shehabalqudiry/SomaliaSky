@extends('layouts.admin')

@section('content')
<div class="col-12 p-3">
	<div class="col-12 col-lg-12 p-0 main-box">

		<div class="col-12 px-0">
			<div class="col-12 p-0 row">
				<div class="col-12 col-lg-4 py-3 px-3">
					<span class="fas fa-subscriptions"></span> الاشتراكات
				</div>
				<div class="col-12 col-lg-4 p-2">
				</div>
				<div class="col-12 col-lg-4 p-2 text-lg-end">
					{{-- @can('create',\App\Models\subscription::class) --}}
					<a href="{{route('admin.subscriptions.create')}}">
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
						<th>الباقة</th>
						<th>العميل</th>
						<th>السعر</th>
						<th>عدد الاعلانات المتاحة</th>
						<th>تم الدفع</th>
						<th>الحالة</th>
						<th>بداية الاشتراك</th>
						<th>انتهاء الاشتراك</th>
						<th>تحكم</th>
					</tr>
				</thead>
				<tbody>
					@foreach($subscriptions as $subscription)
					<tr>
						<td>{{$loop->iteration}}</td>

						<td>{{$subscription->package->title}}</td>
						<td>{{$subscription->user->name }}</td>
						<td>{{$subscription->price}}</td>
						<td>{{$subscription->package->announcement_number - $subscription->user->announcements->count()}} من {{ $subscription->package->announcement_number }}</td>
						<td>
                            @if ($subscription->paid == 1)
                            {{ $subscription->price == 0 ? 'باقة مجانية' : '' }}
                            <span class="fas fa-check-circle text-success"></span>
                            @else
                            <span class="fas fa-times-circle text-danger"></span>
                            @endif
                        </td>
                        <td>
                            @if ($subscription->status == 1)
                            <span class="fas fa-check-circle text-success"></span>
                            @else
                            <span class="fas fa-times-circle text-danger"></span>
                            @endif
                        </td>
                        <td>
                            {{ $subscription->start_date }}
                        </td>

                        <td>
                            {{ $subscription->end_date }}
                        </td>

						<td>
							{{-- @can('update',$subscription) --}}
							<a href="{{route('admin.subscriptions.edit',$subscription)}}">
							<span class="btn  btn-outline-success btn-sm font-1 mx-1">
								<span class="fas fa-wrench "></span> تحكم
							</span>
							</a>
							{{-- @endcan
							@can('delete',$subscription) --}}
							<form method="POST" action="{{route('admin.subscriptions.destroy',$subscription)}}" class="d-inline-block">@csrf @method("DELETE")
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
			{{$subscriptions->appends(request()->query())->render()}}
		</div>
	</div>
</div>
@endsection
