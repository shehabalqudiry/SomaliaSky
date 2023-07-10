@extends('layouts.admin')

@section('content')
<div class="col-12 p-3">
    <div class="col-12 col-lg-12 p-0 ">
        <form id="validate-form" class="row" enctype="multipart/form-data" method="POST" action="{{route('admin.stores.store')}}">
            @csrf
            <div class="col-12 col-lg-8 p-0 main-box">
                <div class="col-12 px-0">
                    <div class="col-12 px-3 py-3">
                        <span class="fas fa-info-circle"></span> إضافة جديد
                    </div>
                    <div class="col-12 divider" style="min-height: 2px;"></div>
                </div>
                <div class="col-12 p-3 row">
                    <div class="col-12 p-2">
                        <div class="col-12">
                            الصورة الشخصية (الشعار - Logo)
                        </div>
                        <div class="col-12 pt-3">
                            <input type="file" name="Avatar" class="form-control" accept="image/*">
                        </div>
                        <div class="col-12 pt-3">
                        </div>
                    </div>

                    <div class="col-12 p-2">
                        <div class="col-12">
                            صورة الغلاف
                        </div>
                        <div class="col-12 pt-3">
                            <input type="file" name="Cover" class="form-control" accept="image/*">
                        </div>
                        <div class="col-12 pt-3">
                        </div>
                    </div>
                    <livewire:category />
                    <livewire:city />
                    <div class="col-12 col-lg-6 my-2">
                        <div class="col-12">
                            المستخدم
                        </div>
                        <div class="col-12 pt-3">
                            <select class="form-control" name="User" required>
                                @foreach($users->where('power', 'USER') as $user)
                                <option value="{{ $user->id }}" @if(old('User') == $user->id) selected @endif>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 p-2">
                        <div class="col-12">
                            الاسم
                        </div>
                        <div class="col-12 pt-3">
                            <input type="text" name="Name" required class="form-control" value="{{old('Name')}}">
                        </div>
                    </div>

                    <div class="col-12 col-lg-12 p-2">
                        <div class="col-12">
                            الموقع
                        </div>
                        <div class="col-12 pt-3">
                            <input type="text" name="Location" required class="form-control" value="{{old('Location')}}">
                        </div>
                    </div>
                    <div class="col-12 p-2">
                        <div class="col-12">
                            الوصف
                        </div>
                        <div class="col-12 pt-3">
                            <textarea name="Description" class="form-control">{{old('Description')}}</textarea>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 p-2">
                        <div class="col-12">
                            توثيق المتجر (اعطاء شارة التوثيق <span class="fas fa-check-circle text-primary"></span>)
                        </div>
                        <div class="col-12 pt-3">
                            <select class="form-control" name="is_featured">
                                <option @if(old('is_featured')=="0" ) selected @endif value="0">لا</option>
                                <option @if(old('is_featured')=="1" ) selected @endif value="1">نعم</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 p-2">
                        <div class="col-12">
                            تفعيل/حظر المتجر
                        </div>
                        <div class="col-12 pt-3">
                            <select class="form-control" name="blocked">
                                <option @if(old('blocked')=="0" ) selected @endif value="0">تفعيل المتجر</option>
                                <option @if(old('blocked')=="1" ) selected @endif value="1">حظر</option>
                                <option @if(old('blocked')=="2" ) selected @endif value="2"> إيقاف تفعيل المتجر</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 p-3">
                <button class="btn btn-success" id="submitEvaluation">حفظ</button>
            </div>
        </form>
    </div>
</div>
@endsection
