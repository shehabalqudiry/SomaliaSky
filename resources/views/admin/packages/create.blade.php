@extends('layouts.admin')

@section('content')
    <div class="col-12 p-3">
        <div class="col-12 col-lg-12 p-0 ">


            <form id="validate-form" class="row" enctype="multipart/form-data" method="POST"
                action="{{ route('admin.packages.store') }}">
                @csrf

                <div class="col-12 col-lg-8 p-0 main-box">
                    <div class="col-12 px-0">
                        <div class="col-12 px-3 py-3">
                            <span class="fas fa-info-circle"></span> إضافة جديد
                        </div>
                        <div class="col-12 divider" style="min-height: 2px;"></div>
                    </div>
                    {{-- More Language --}}
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        @foreach (config('laravellocalization.supportedLocales') as $key => $lang)
                            <li class="nav-item mx-auto" role="presentation">
                                <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="pills-{{ $key }}-tab"
                                    data-bs-toggle="pill" href="#pills-{{ $key }}" role="tab"
                                    aria-controls="pills-{{ $key }}" aria-selected="true">{{ $lang['native'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        @foreach (config('laravellocalization.supportedLocales') as $key => $lang)
                            <div class="tab-pane fade show {{ $loop->first ? 'active' : '' }}"
                                id="pills-{{ $key }}" role="tabpanel"
                                aria-labelledby="pills-{{ $key }}-tab">
                                <div class="col-12 col-lg-12 p-2">
                                    <div class="col-12">
                                        اسم الباقة
                                    </div>
                                    <div class="col-12 pt-3">
                                        <input type="text" name="{{ $key }}[title]" required
                                            class="form-control" value="{{ old($key.'.title') }}">
                                    </div>
                                </div>

                                <div class="col-12 col-lg-6 p-2">
                                    <div class="col-12">
                                        وصف
                                    </div>
                                    <div class="col-12 pt-3">
                                        <input type="text" name="{{ $key }}[description]" maxlength="255" class="form-control"
                                            value="{{ old($key.'.description') }}">
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>


                    <div class="col-12 p-3 row">
                        <div class="col-12 col-lg-6 p-2">
                            <div class="col-12">
                                السعر
                            </div>
                            <div class="col-12 pt-3">
                                <input type="number" name="price" class="form-control" value="{{ old('price') }}">
                            </div>
                        </div>

                    </div>

                    <div class="col-12 p-3 row">
                        <div class="col-12 col-lg-6 p-2">
                            <div class="col-12">
                                عدد الاعلانات المتاحة (في حالة لم يتم ادخال قيمة القيمة الافتراضية 10 اعلانات)
                            </div>
                            <div class="col-12 pt-3">
                                <input type="number" name="announcement_number" class="form-control"
                                    value="{{ old('announcement_number') }}">
                            </div>
                        </div>

                    </div>

                    <div class="col-12 p-3 row">
                        <div class="col-12 col-lg-6 p-2">
                            <div class="col-12">
                                مدة الباقة بالايام ( في حالة لم يتم ادخال قيمة القيمة الافتراضية 30 يوم)
                            </div>
                            <div class="col-12 pt-3">
                                <input type="number" name="time" class="form-control" value="{{ old('time') }}">
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
