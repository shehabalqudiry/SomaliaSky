@extends('layouts.admin')
@section('content')
    <div class="col-12 p-3">
        <div class="col-12 col-lg-12 p-0 ">


            <form id="validate-form" class="row" enctype="multipart/form-data" method="POST"
                action="{{ route('admin.categories.store') }}">
                @csrf

                <div class="col-12 col-lg-6 p-0 main-box">
                    <div class="col-12 px-0">
                        <div class="col-12 px-3 py-3">
                            <span class="fas fa-info-circle"></span> إضافة جديد
                        </div>
                        <div class="col-12 divider" style="min-height: 2px;"></div>
                    </div>
                    <div class="col-12 p-3 row">
                        <div class="col-12 p-2">
                            <div class="col-12">
                                القسم الرئيسي
                            </div>
                            <div class="col-12 pt-3">
                                <select name="parent_id" class="form-control">
                                    <option value="">هذا قسم رئيسي</option>
                                    @foreach (\App\Models\Category::where('parent_id', null)->get() as $category)
                                        <option value="{{ $category->id }}"
                                            @if (request()->category_id && request()->category_id == $category->id) selected @endif>{{ $category->title }}
                                        </option>
                                    @endforeach
                                </select>
                                <span>اختار هذا اذا كان القسم فرعي واتركه اذا كان رئيسي</span>
                            </div>
                        </div>
                        <div class="col-12 p-2">
                            <div class="col-12">
                                الصورة
                            </div>
                            <div class="col-12 pt-3">
                                <input type="file" name="image" class="form-control" accept="image/*">
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
                            @foreach (config('laravellocalization.supportedLocales') as $key => $lang)
                                <li class="nav-item mx-auto" role="presentation">
                                    <a class="nav-link {{ $loop->first ? 'active' : '' }}"
                                        id="pills-{{ $key }}-tab" data-bs-toggle="pill"
                                        href="#pills-{{ $key }}" role="tab"
                                        aria-controls="pills-{{ $key }}"
                                        aria-selected="true">{{ $lang['native'] }}</a>
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
                                            العنوان
                                        </div>
                                        <div class="col-12 pt-3">
                                            <input type="text" name="{{ $key }}[title]" required
                                                class="form-control" value="{{ old($key . '.title') }}">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
                <div class="col-12 col-lg-6 p-0 mt-sm-3">
                    <div class="repeater text-center">
                        <h4>خصائص اخرى</h4>
                        <div data-repeater-list="category_attributes" class="px-2">
                            @for ($i = 0; $i <= 10; $i++)
                                <div data-repeater-item>
                                    {{-- {{ $i + 1 }} --}}
                                    {{-- More Language --}}
                                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                        @foreach (config('laravellocalization.supportedLocales') as $key => $lang)
                                            <li class="nav-item mx-auto" role="presentation">
                                                <a class="nav-link {{ $loop->first ? 'active' : '' }}"
                                                    id="pills{{ $i }}-{{ $key }}-tab" data-bs-toggle="pill"
                                                    href="#pills{{ $i }}-{{ $key }}" role="tab"
                                                    aria-controls="pills{{ $i }}-{{ $key }}"
                                                    aria-selected="true">{{ $lang['native'] }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="tab-content" id="pills{{ $i }}-tabContent">
                                        @foreach (config('laravellocalization.supportedLocales') as $key2 => $lang)
                                            <div class="tab-pane fade show {{ $loop->first ? 'active' : '' }}"
                                                id="pills{{ $i }}-{{ $key2 }}" role="tabpanel"
                                                aria-labelledby="pills{{ $i }}-{{ $key2 }}-tab">

                                                <input class="form-control mb-2 d-inline-block w-75" type="text"
                                                    name="[{{ $key2 }}.attr]" placeholder="اسم الحقل المطلوب"
                                                    value="" />

                                            </div>
                                        @endforeach
                                        <input data-repeater-delete type="button" class="btn btn-danger mb-2"
                                            value="حذف" />
                                    </div>
                                </div>
                            @endfor
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
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"></script>
    <script>
        $('.repeater').repeater();
    </script>
@endsection
