@extends('layouts.admin')
@section('content')
<div class="col-12 p-3">
    <div class="col-12 col-lg-12 p-0 ">
        <form id="validate-form" class="row" enctype="multipart/form-data" method="POST" action="{{route('admin.articles.store')}}">
            @csrf
            <div class="col-12 col-lg-12 p-0 main-box">
                <div class="col-12 px-0">
                    <div class="col-12 px-3 py-3">
                        <span class="fas fa-info-circle"></span> إضافة جديد
                    </div>
                    <div class="col-12 divider" style="min-height: 2px;"></div>
                </div>
                <div class="col-12 p-3 row">
                    <div class="col-12 col-lg-6 p-2">
                        <div class="col-12">
                            القسم
                        </div>
                        <div class="col-12 pt-3">
                            <select class="form-control select2-select" name="category_id[]" required multiple size="1" style="height:30px;opacity: 0;">
                                @foreach($categories as $category)
                                <option value="{{$category->id}}" @if(old('category_id')==$category->id) selected @endif>{{$category->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 p-2">
                        <div class="col-12">
                            مميز
                        </div>
                        <div class="col-12 pt-3">
                            <select class="form-control" name="is_featured">
                                <option @if(old('is_featured')=="0" ) selected @endif value="0">لا</option>
                                <option @if(old('is_featured')=="1" ) selected @endif value="1">نعم</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 p-2">
                        <div class="col-12">
                            الصورة الرئيسية
                        </div>
                        <div class="col-12 pt-3">
                            <input type="file" name="main_image" class="form-control" accept="image/*">
                        </div>
                        <div class="col-12 pt-3">
                        </div>
                    </div>

                    <div class="col-12 p-2">
                        <div class="col-12">
                            <h3>ترجمة البيانات</h3>
                        </div>
                        <br />
                    </div>

                    {{-- More Language --}}
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        @foreach (config("laravellocalization.supportedLocales") as $key => $lang)
                        <li class="nav-item mx-auto" role="presentation">
                          <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="pills-{{ $key }}-tab" data-bs-toggle="pill" href="#pills-{{ $key }}" role="tab" aria-controls="pills-{{ $key }}" aria-selected="true">{{ $lang['native'] }}</a>
                        </li>
                        @endforeach
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                    @foreach (config("laravellocalization.supportedLocales") as $key => $lang)
                        <div class="tab-pane fade show {{ $loop->first ? 'active' : '' }}" id="pills-{{ $key }}" role="tabpanel" aria-labelledby="pills-{{ $key }}-tab">
                            <div class="col-12 col-lg-12 p-2">
                                <div class="col-12">
                                    الرابط
                                </div>
                                <div class="col-12 pt-3">
                                    <input type="text" name="{{ $key }}[slug]" required class="form-control" value="{{old($key . '.slug')}}">
                                </div>
                            </div>
                            <div class="col-12 col-lg-12 p-2">
                                <div class="col-12">
                                    العنوان
                                </div>
                                <div class="col-12 pt-3">
                                    <input type="text" name="{{ $key }}[title]" required class="form-control" value="{{old($key . '.title')}}">
                                </div>
                            </div>
                            <div class="col-12  p-2">
                                <div class="col-12">
                                    الوصف
                                </div>
                                <div class="col-12 pt-3">
                                    <textarea name="{{ $key }}[description]" class="editor with-file-explorer">{{old($key . '.description')}}</textarea>
                                </div>
                            </div>
                            <div class="col-12 p-2">
                                <div class="col-12">
                                    ميتا الوصف
                                </div>
                                <div class="col-12 pt-3">
                                    <textarea name="{{ $key }}[meta_description]" class="form-control" style="min-height:150px">{{old($key . '.meta_description')}}</textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach
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
