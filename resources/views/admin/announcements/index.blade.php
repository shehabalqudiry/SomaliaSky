@extends('layouts.admin')
@section('content')
    <div class="col-12 p-3">
        <div class="col-12 col-lg-12 p-0 main-box">

            <div class="col-12 px-0">
                <div class="col-12 p-0 row">
                    <div class="col-12 col-lg-4 py-3 px-3">
                        <span class="fas fa-contacts"></span> الاعلانات
                    </div>
                    <div class="col-12 col-lg-4 p-2">
                    </div>
                </div>
                <div class="col-12 divider" style="min-height: 2px;"></div>
            </div>

            <div class="col-12 py-2 px-2 row justify-content-between">
                <div class="col-12 col-lg-4 p-2">
                    <form method="GET">
                        <input type="text" name="q" class="form-control" placeholder="بحث ... ">
                    </form>
                </div>
                <div class="col-12 col-lg-4 px-2 justify-content-end d-flex mb-2">
                    @can('create', \App\Models\Announcement::class)
                        <a href="{{ route('admin.announcements.create') }}">
                            <button class="btn btn-primary pb-2 rounded-0"><span class="fas fa-plus"></span> إضافة
                                إعلان</button>
                        </a>
                    @endcan
                </div>
            </div>
            <div class="col-12 p-3" style="overflow:auto">
                <div class="col-12 p-0" style="min-width:1100px;">


                    <table class="table table-bordered  table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">العنوان</th>
                                <th scope="col">السعر</th>
                                <th scope="col">المستخدم</th>
                                <th scope="col">نوع الاعلان</th>
                                <th scope="col">القسم</th>
                                <th scope="col">المدينة</th>
                                <th scope="col">مميز</th>
                                <th scope="col">حالة الاعلان</th>
                                <th scope="col">تحكم</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($announcements as $announcement)
                                <tr>
                                    <td scope="col">{{ $loop->iteration }}</td>
                                    <td scope="col">{{ $announcement->title }}</td>
                                    <td scope="col">{{ $announcement->price }}</td>
                                    <td scope="col">{{ $announcement->user->name ?? '' }}</td>
                                    <td scope="col">{{ $announcement->type == 1 ? 'متجر' : 'شخصي' }}</td>
                                    <td scope="col">{{ $announcement->getCategory() }}</td>
                                    <td scope="col">
                                        {{ $announcement->city ? $announcement->city->country->name . ' - ' . $announcement->city->name : 'تم حذف المدينة' }}
                                    </td>
                                    <td scope="col" style="font-size: 16px">
                                        @if ($announcement->is_featured == 0)
                                            <span class="fas fa-times-circle text-danger"></span>
                                        @else
                                            <span class="fas fa-check-circle text-primary"></span>
                                            {{ $announcement->is_featured }}
                                        @endif
                                    </td>
                                    <td scope="col" style="font-size: 16px">
                                        @if ($announcement->status == 0)
                                            <span class="fas fa-times-circle text-danger"></span> تم إيقاف مؤقتاً
                                        @else
                                            <span class="fas fa-check-circle text-primary"></span> جاري العرض ...
                                        @endif
                                    </td>
                                    <td style="width: 200px">

                                        <a href="{{ route('admin.announcements.edit', $announcement) }}">
                                            <span class="btn btn-outline-success btn-sm font-1 mx-1">
                                                <span class="fas fa-wrench "></span> تحكم
                                            </span>
                                        </a>

                                        <form method="POST"
                                            action="{{ route('admin.announcements.destroy', $announcement) }}"
                                            class="d-inline-block">@csrf @method('DELETE')
                                            <button class="btn  btn-outline-danger btn-sm font-1 mx-1"
                                                onclick="var result = confirm('هل أنت متأكد من عملية الحذف ؟');if(result){}else{event.preventDefault()}">
                                                <span class="fas fa-trash "></span> حذف
                                            </button>
                                        </form>


                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="col-12 px-0 py-2">
                        {{ $announcements->appends(request()->query())->render() }}
                    </div>
                </div>
            </div>
        @endsection
