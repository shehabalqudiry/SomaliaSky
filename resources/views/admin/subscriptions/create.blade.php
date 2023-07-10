@extends('layouts.admin')

@section('content')
    <div class="col-12 p-3">
        <div class="col-12 col-lg-12 p-0 ">


            <form id="validate-form" class="row" enctype="multipart/form-data" method="POST"
                action="{{ route('admin.subscriptions.store') }}">
                @csrf

                <div class="col-12 col-lg-8 p-0 main-box">
                    <div class="col-12 px-0">
                        <div class="col-12 px-3 py-3">
                            <span class="fas fa-info-circle"></span> إضافة جديد
                        </div>
                        <div class="col-12 divider" style="min-height: 2px;"></div>
                    </div>
                    <div class="col-12 p-3 row">
                        <div class="col-12">
                            المستخدم
                        </div>
                        <div class="col-12 pt-3">
                            <select class="form-control" name="user_id" required>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @if (old('user_id') == $user->id) selected @endif>
                                        {{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 p-3 row">
                        <div class="col-12">
                            الباقة
                        </div>
                        <div class="col-12 pt-3">
                            <select class="form-control" name="package_id" required>
                                @foreach ($packages as $package)
                                    <option value="{{ $package->id }}" @if (old('package_id') == $package->id) selected @endif>
                                        {{ $package->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 p-3 row">
                        <div class="col-12 col-lg-6 p-2">
                            <div class="col-12">
                                التكلفة (اتركه فارغ ليأخذ قيمة هذا الحقل من الباقة المختارة)
                            </div>
                            <div class="col-12 pt-3">
                                <input type="number" name="price" class="form-control" value="{{ old('price') }}">
                            </div>
                        </div>

                    </div>

                    <div class="col-12 my-2 row">
                        <div class="col-12">
                            الحالة
                        </div>
                        <div class="col-12 pt-3">
                            <div class="form-switch">
                                <input name="status" class="form-check-input" type="checkbox" id="flexSwitchCheckDefault"
                                    checked {{ old('status') == 1 ? 'checked' : '' }} style="width: 50px;height:25px"
                                    value="1">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 my-2 row">
                        <div class="col-12">
                            مدفوع ؟
                        </div>
                        <div class="col-12 pt-3">
                            <div class="form-switch">
                                <input name="paid" class="form-check-input" type="checkbox" id="flexSwitchCheckDefault"
                                    checked {{ old('paid') == 1 ? 'checked' : '' }} style="width: 50px;height:25px"
                                    value="1">
                            </div>
                        </div>
                    </div>

                    <div class="col-12 p-3 row">
                        <div class="col-12 col-lg-6 p-2">
                            <div class="col-12">
                                البداية (في حالة لم يتم ادخال قيمة القيمة الافتراضية هي التوقيت الحالي)
                            </div>
                            <div class="col-12 pt-3">
                                <input type="date" name="start_date" class="form-control"
                                    value="{{ old('start_date') }}">
                            </div>
                        </div>

                    </div>
                    <div class="col-12 p-3 row">
                        <div class="col-12 col-lg-6 p-2">
                            <div class="col-12">
                                نهاية الاشتراك (في حالة لم يتم ادخال قيمة القيمة الافتراضية هي التوقيت الحالي + مدة الباقة)
                            </div>
                            <div class="col-12 pt-3">
                                <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
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
