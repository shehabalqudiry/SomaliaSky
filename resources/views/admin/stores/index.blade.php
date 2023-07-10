@extends('layouts.admin')
@section('content')
    <div class="col-12 p-3">
        <div class="col-12 col-lg-12 p-0 main-box">

            <div class="col-12 px-0">
                <div class="col-12 p-0 row">
                    <div class="col-12 col-lg-4 py-3 px-3">
                        <span class="fas fa-stores"></span> المتاجر
                    </div>
                    <div class="col-12 col-lg-4 p-2">
                    </div>
                    <div class="col-12 col-lg-4 p-2 text-lg-end">
                        {{-- @can('create', \App\Models\store::class) --}}
                        <a href="{{ route('admin.stores.create') }}">
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
                                <th>اسم المتجر</th>
                                <th>القسم</th>
                                <th>العنوان</th>
                                <th>الشعار</th>
                                <th>موثق</th>
                                <th>تحكم</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stores as $store)
                                <tr>
                                    <td>{{ $store->id }}</td>
                                    <td>{{ $store->name }}</td>
                                    <td>
                                        {{ $store->getCategory() }}
                                    </td>
                                    <td>{{ $store->city ? $store->city->name . ' - ' . $store->city->country->name : '' }}
                                    </td>
                                    <td><img src="{{ $store->avatar_image() }}" style="width:70px"></td>
                                    <td>
                                        @if ($store->is_featured == 1)
                                            <span class="fas fa-check-circle text-primary"></span>
                                        @else
                                            <span class="fas fa-times-circle text-danger"></span>
                                        @endif
                                    </td>
                                    <td style="width: 270px;">

                                        @can('view', $store)
                                            <a href="{{ route('store.show', ['store' => $store]) }}">
                                                <span class="btn  btn-outline-primary btn-sm font-1 mx-1">
                                                    <span class="fas fa-search "></span> عرض
                                                </span>
                                            </a>
                                        @endcan

                                        {{-- @can('update', $store) --}}
                                        <a href="{{ route('admin.stores.edit', $store) }}">
                                            <span class="btn  btn-outline-success btn-sm font-1 mx-1">
                                                <span class="fas fa-wrench "></span> تحكم
                                            </span>
                                        </a>
                                        {{-- @endcan --}}
                                        {{-- @can('delete', $store) --}}
                                        <form method="POST" action="{{ route('admin.stores.destroy', $store) }}"
                                            class="d-inline-block">@csrf @method('DELETE')
                                            <button class="btn  btn-outline-danger btn-sm font-1 mx-1"
                                                onclick="var result = confirm('هل أنت متأكد من عملية الحذف ؟');if(result){}else{event.preventDefault()}">
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
                {{ $stores->appends(request()->query())->render() }}
            </div>
        </div>
    </div>
@endsection
