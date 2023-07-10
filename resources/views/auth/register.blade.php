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
                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                    @csrf
                    <h3 class="my-5 text-center mainColor">{{ __('lang.register') }}</h3>
                    <div class="row">
                        <div class="col-12 col-md-8">
                            <div class="form-group row pb-3">
                                <div class="col-12 col-md-6">
                                    <label for="name" class="col-form-label text-md-end">{{ __('lang.Name') }}</label>
                                    <input id="name" type="text" placeholder="{{ __('lang.Name') }}" class="form-control rounded-3 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="phone" class="col-form-label text-md-end">{{ __('lang.phone') }}</label>
                                    <input id="phone" type="number" placeholder="962780997333" class="text-start form-control rounded-3 @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone" autofocus>
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row pb-3">
                                <div class="col-12 col-md-6">
                                    <label for="email2" class="col-form-label text-md-end">{{ __('lang.email') }}</label>
                                    <input id="email2" placeholder="{{ __('lang.email') }}" type="email" class="text-start form-control rounded-3 @error('email') is-invalid @enderror" value="{{ old('email') }}" name="email" required autocomplete="email">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="password2" class="col-form-label text-md-end">{{ __('lang.password') }}</label>
                                    <input id="password2" type="password" placeholder="************" class="form-control rounded-3 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row pb-3">
                                <livewire:city :country_id="old('Country')" />
                            </div>
                        </div>
                        <div class="col-12 col-lg-4 text-center">
                            <livewire:upload-photo-with-preview />
                        </div>
                        <div class="col-12 col-lg-8 mx-auto my-3 py-4 px-5 text-center">
                            <button type="submit" class="btn rounded-5 mainBgColor">
                                {{ __('lang.register') }}
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
