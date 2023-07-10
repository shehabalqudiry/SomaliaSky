@extends('layouts.admin')
@section('content')
    <div class="col-12 p-3">
        <div class="col-12 col-lg-12 p-0 ">


            <form id="validate-form" class="row" enctype="multipart/form-data" method="POST"
                action="{{ route('admin.sliders.update', $slider) }}">
                @csrf
                @method('PUT')


                <div class="col-12 col-lg-6 p-0 main-box">
                    <div class="col-12 px-0">
                        <div class="col-12 px-3 py-3">
                            <span class="fas fa-info-circle"></span> تعديل
                        </div>
                        <div class="col-12 divider" style="min-height: 2px;"></div>
                    </div>
                    <div class="col-12 p-3 row">
                        <div class="col-12 p-2">
                            <div class="col-12">
                                الصورة
                            </div>
                            <div class="col-12 pt-3">
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                            <div class="col-12 pt-3">
                                <img src="{{ $slider->image() }}" style="width:150px">
                            </div>
                        </div>
                        <div class="col-12 p-2">
                            <div class="col-12">
                                الرابط
                            </div>
                            <div class="col-12 pt-3">
                                <input type="text" name="link" required maxlength="190" class="form-control"
                                    value="{{ $slider->link }}">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-12 p-3 mt-5">
                    <button class="btn btn-success btn-block" id="submitEvaluation">حفظ</button>
                </div>
            </form>
        </div>
    </div>
@endsection
