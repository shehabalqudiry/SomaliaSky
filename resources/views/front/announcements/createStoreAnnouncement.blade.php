@extends('layouts.app')
@section('styles')
    <style>
        .search {
            display: none !important;
        }

        .nav-pills .nav-item .nav-link.active {
            color: #fff !important;
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row pt-5">
            <div class="col-12" style="">
                <div class="col-12">
                    <form method="POST" action="{{ route('front.announcements.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="1">
                        <div class="row">
                            <div class="col-8 pt-5">
                                <h3 class="mb-3 mainColor">{{ __('lang.AddAnnouncement') }}</h3>
                            </div>
                            <div class="col-4 mt-0 mb-5 text-center">
                                <span class="fas fa-exclamation-triangle font-6 mb-3 d-block"></span>
                                {{ __('lang.Note Info') }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group row mb-4">
                                    <div class="col-12 p-2">
                                        <div class="col-12">
                                            <h3>{{ __('lang.transAds') }}</h3>
                                        </div>
                                        <br />
                                    </div>

                                    {{-- More Language --}}
                                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                        @foreach (config('laravellocalization.supportedLocales') as $key => $lang)
                                            <li class="nav-item mx-auto" role="presentation">
                                                <a class="nav-link {{ $key == app()->getLocale() ? 'active' : '' }}"
                                                    id="pills-{{ $key }}-tab" data-bs-toggle="pill"
                                                    href="#pills-{{ $key }}" role="tab"
                                                    aria-controls="pills-{{ $key }}"
                                                    aria-selected="true">{{ __('lang.Language') . ' ' . $lang['native'] }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="tab-content " id="pills-tabContent">
                                        @foreach (config('laravellocalization.supportedLocales') as $key => $lang)
                                            <div class="tab-pane fade show {{ $key == app()->getLocale() ? 'active' : '' }}"
                                                id="pills-{{ $key }}" role="tabpanel"
                                                aria-labelledby="pills-{{ $key }}-tab">
                                                {{-- <div class="col-12 col-lg-12 p-2">
                                                    <div class="col-12">
                                                        العنوان
                                                    </div>
                                                    <div class="col-12 pt-3">
                                                        <input type="text" name="{{ $key }}[title]" required
                                                            class="form-control" value="{{ old($key . '.title') }}">
                                                    </div>
                                                </div> --}}
                                                <div class="col-8 mx-auto text-center">

                                                    <div class="col-12">
                                                        <label for="Title"
                                                            class="col-form-label text-md-end">{{ __('lang.Announcement Title') }}</label>
                                                        <input id="Title" type="text"
                                                            placeholder="{{ __('lang.Announcement Title') }}"
                                                            class="form-control rounded-3 @error($key . '.title') is-invalid @enderror"
                                                            name="{{ $key }}[title]"
                                                            value="{{ old($key . '.title') }}">
                                                        @error($key . '.title')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="Description"
                                                            class="col-form-label text-md-end">{{ __('lang.Announcement Desc') }}</label>
                                                        <textarea id="Description" rows="6" placeholder="{{ __('lang.Announcement Desc') }}"
                                                            class="form-control rounded-3 @error($key . 'Description') is-invalid @enderror"
                                                            name="{{ $key }}[Description]">{{ old($key . 'Description') }}</textarea>
                                                        @error($key . 'Description')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-group row pb-3">
                                    <div class="col-12 px-0">
                                        @if (auth()->user()->store)
                                            <livewire:category :subcategory_id="auth()->user()->store->category->id" :category_id="auth()->user()->store->category->category->id" />
                                        @else
                                            <livewire:category />
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row pb-3">
                                    <div class="col-6 px-0">
                                        @if (auth()->user()->city)
                                            <livewire:city :country_id="auth()->user()->city->country_id" />
                                        @else
                                            <livewire:city />
                                        @endif
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label for="Price"
                                            class="col-form-label text-md-end">{{ __('lang.price') }}</label>
                                        <input id="Price" type="text" placeholder="$ {{ __('lang.price') }}"
                                            class="form-control rounded-3 @error('price') is-invalid @enderror"
                                            name="price" value="{{ old('price') }}" required>
                                        @error('price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label for="currency"
                                            class="col-form-label text-md-end">{{ __('lang.Currency') }}</label>
                                        <select name="currency" class="form-control rounded-3 @error('currency') is-invalid @enderror" value="{{ old('currency') }}" required>
                                            @foreach (App\Models\Currency::get() as $currency)
                                            <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('currency')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row pb-3">
                                    <div class="col-12 col-md-4">
                                        <label for="photos"
                                            class="col-form-label text-md-end">{{ __('lang.Add') . ' ' . __('lang.photos') }}</label>
                                        <div class="col-12 mt-2" style="overflow: hidden">
                                            <div class="col-12 px-0" id="file-uploader-nafezly-second">
                                                <input type="hidden" disabled class="file-uploader-uploaded-files">
                                                <input name="file" type="file" multiple class="file-uploader-files"
                                                    data-fileuploader-files="" style="opacity: 0" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4 px-4 text-center pt-5 mb-5">
                                        <div class="pt-4">
                                            <label for="accept" class="mb-4">
                                                <input type="checkbox" id="accept" required name="accept_rules">
                                                <a class="text-primary"
                                                    href="#">{{ __('lang.I accept the terms of service') }}</a>
                                            </label>
                                            <button type="submit" class="btn w-75 rad14 mainBgColor">
                                                {{ __('lang.Continue') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @include('admin.templates.dropzone', [
        'selector' => '#file-uploader-nafezly-second',
        'url' => route('front.upload.file'),
        'method' => 'POST',
        'remove_url' => route('front.upload.remove-file'),
        'remove_method' => 'POST',
        'enable_selector_after_upload' => '#submitEvaluation',
        'max_files' => 25,
        'max_file_size' => 1024,
        'accepted_files' => "['image/*']",
    ])
@endsection
