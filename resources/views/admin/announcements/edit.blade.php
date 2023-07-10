@extends('layouts.admin')
@section('content')
    <div class="col-12 p-3">
        <div class="col-12 col-lg-9 p-4 mx-auto main-box">

            <form id="validate-form" method="POST" class="row"
                action="{{ route('admin.announcements.update', $announcement) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="col-12 px-3">
                    <h4>إضافة إعلان</h4>
                </div>
                <livewire:city :country_id="$announcement->city->country_id ?? null" :city_id="$announcement->city->id ?? null" />
                <livewire:category :subcategory_id="$announcement->category->id ?? null" :category_id="$announcement->category->category->id ?? null" :catAttributes="$announcement" />
                <div class="col-12 col-lg-12 p-0">

                    <div class="row pt-0">
                        <div class="col-12 col-lg-6 my-2">
                            <div class="col-12">
                                المستخدم
                            </div>
                            <div class="col-12 pt-3">
                                <select class="form-control" required name="user_id">
                                    {{-- <option value="" selected>المستخدم</option> --}}
                                    <option value="{{ $announcement->user->id }}">{{ $announcement->user->name }}</option>
                                    {{-- @foreach ($users as $user) --}}
                                    {{-- @endforeach --}}
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 my-2">
                            <div class="col-12">
                                نوع الاعلان
                            </div>
                            <div class="col-12 pt-3">
                                <select class="form-control" required name="type">
                                    <option value="" selected>نوع الاعلان</option>
                                    <option value="0" @if ($announcement->type == 0) selected @endif>شخصي</option>
                                    <option value="1" @if ($announcement->type == 1) selected @endif>تابع للمتجر
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 my-2">
                            <div class="col-12">
                                السعر
                            </div>
                            <div class="col-12 pt-3">
                                <input class="form-control" required name="price" pattern="[0-9]"
                                    value="{{ $announcement->price }}" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="row pt-0">
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
                                    <div class="col-12 col-lg-12 my-2">
                                        <div class="col-12">
                                            نص الاعلان
                                        </div>
                                        <div class="col-12 pt-3">
                                            <input type="text" name="{{ $key }}[title]" class="form-control"
                                                value="{{ $announcement->getTranslation('title', $key) }}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-12 my-2">
                                        <div class="col-12">
                                            الوصف
                                        </div>
                                        <div class="col-12 pt-3">
                                            <textarea class="form-control" name="{{ $key }}[description]" style="min-height: 200px">{{ $announcement->getTranslation('description', $key) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-8 my-2">
                            <div class="col-12">
                                تميز الاعلان (0 => غير مميز) -- (1 - 10 => مميز بواقع الاصغر هو الاعلى تميزا)
                            </div>
                            <div class="col-12 pt-2">
                                <input name="is_featured" required min="0" max="10" class="form-control"
                                    type="number" value="{{ $announcement->is_featured }}">
                            </div>
                        </div>
                        <div class="col-12 col-lg-4 my-2">
                            <div class="col-12">
                                ايقاف/تشغيل الاعلان
                            </div>
                            <div class="col-12 pt-3">
                                <div class="form-switch">
                                    <input name="status" class="form-check-input" type="checkbox"
                                        id="flexSwitchCheckDefault" {{ $announcement->status == 1 ? 'checked' : '' }}
                                        style="width: 50px;height:25px" value="1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-12 my-2">
                        <div class="col-12">
                            الصور
                        </div>
                        <div class="col-12  px-0 mt-2 px-0">
                            <div class="col-12 mt-2" style="overflow: hidden">
                                <div class="col-12 px-0" id="file-uploader-nafezly-second">
                                    <input type="hidden" disabled class="file-uploader-uploaded-files">
                                    <input name="file" type="file" multiple class="file-uploader-files"
                                        data-fileuploader-files="" style="opacity: 0" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            {{-- @dd(collect($announcement->imagesArray())) --}}
                            @foreach (explode(',', $announcement->images) as $img)
                                <div class="col-12 col-lg-{{ 12 / count($announcement->imagesArray()) }}">
                                    <img src="{{ env('STORAGE_URL') . '/uploads/uploads/' . $img }}" alt=""
                                        class="img-fluid">
                                    {{-- <button class="btn btn-danger" onclick="deleteItem('{{ $img }}')" type="button">حذف</button> --}}
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-12 col-lg-12 my-4">
                        <div class="col-12 pt-3">
                            <button class="btn btn-success btn-block" id="submitEvaluation">حفظ</button>
                        </div>

                    </div>


                </div>
            </form>


        </div>
    </div>
@endsection
@section('scripts')
    @include('admin.templates.dropzone', [
        'selector' => '#file-uploader-nafezly-second',
        'url' => route('admin.upload.file'),
        'method' => 'POST',
        'remove_url' => route('admin.upload.remove-file'),
        'remove_method' => 'POST',
        'enable_selector_after_upload' => '#submitEvaluation',
        'max_files' => 100,
        'max_file_size' => 100000000,
        'accepted_files' => "['image/*']",
    ])

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"></script>
    <script type="text/javascript">
        $('.repeater').repeater({
            initEmpty: false,
        });
    </script>
@endsection
