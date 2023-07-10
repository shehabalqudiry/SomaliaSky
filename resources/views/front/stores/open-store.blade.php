@extends('layouts.app')
@section('styles')
<style>
    .search{
        display:none !important;
    }
</style>
@endsection
@section('content')
<div class="container">
    <div class="row pt-5">
        <div class="col-12" style="">
            <div class="col-12">
                <form method="POST" action="{{ route('front.store.my_store_save') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-8 pt-5">
                            <h3 class="mb-3 mainColor">{{ __('lang.OpenStore') }}</h3>
                            <p class="mb-5">{{ __('lang.Store msg') }}</p>
                        </div>
                        <div class="col-4 mt-0 mb-5 text-center">
                            <livewire:upload-photo-with-preview />
                            {{ __('lang.Add') . ' ' .__('lang.Store Logo') }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group row pb-3">
                                <div class="col-12 col-md-4">
                                    <label for="Name" class="col-form-label text-md-end">{{ __('lang.Store Name') }}</label>
                                    <input id="Name" type="text" placeholder="{{ __('lang.Store Name') }}" class="form-control rounded-3 @error('Name') is-invalid @enderror" name="Name" value="{{ old('Name') }}" required>
                                    @error('Name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-8 px-0">
                                    <livewire:category />
                                </div>
                            </div>
                            <div class="form-group row pb-3">
                                <div class="col-8 px-0">
                                    <livewire:city />
                                </div>
                                <div class="col-12 col-md-4">
                                    <label for="Location" class="col-form-label text-md-end">{{ __('lang.Store location') }}</label>
                                    <input id="Location" type="text" placeholder="{{ __('lang.Store location') }}" class="form-control rounded-3 @error('Location') is-invalid @enderror" name="Location" value="{{ old('Location') }}" required>
                                    @error('Location')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row pb-3">
                                <div class="col-12 col-md-4">
                                    <label for="photos" class="col-form-label text-md-end">{{ __('lang.Cover') }}</label>
                                    <div class="col-12 mt-2" style="overflow: hidden">
                                        <div class="col-12 px-0" id="file-uploader-nafezly-second">
                                            <input type="hidden" disabled class="file-uploader-uploaded-files">
                                            <input name="file" type="file" class="file-uploader-files"
                                                data-fileuploader-files="" style="opacity: 0" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label for="Description" class="col-form-label text-md-end">{{ __('lang.Store Desc') }}</label>
                                    <textarea id="Description" rows="6" placeholder="{{ __('lang.Store Desc') }}" class="form-control rounded-3 @error('desc') is-invalid @enderror" name="Description" required>{{ old('Description') }}</textarea>
                                    @error('Description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-4 px-4 text-center pt-5 mb-5">
                                    <div class="pt-4">
                                        <label for="accept" class="mb-4">
                                            <input type="checkbox" id="accept" required name="accept_rules">
                                            <a class="text-primary" href="#">{{ __('lang.I accept the terms of service') }}</a>
                                        </label>
                                        <button type="submit" class="btn w-75 rad14 mainBgColor">
                                            {{ __('lang.Store Button') }}
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
        'max_files' => 1,
        'max_file_size' => 1024,
        'accepted_files' => "['image/*']",
    ])

@endsection
