@extends('layouts.app')
@section('styles')
<style>
    .search, nav{
        display:none !important;
    }
</style>
@endsection
@section('content')
<div class="row">
    <div class="col-12 col-md-5 mx-auto text-start px-3" style="">
        <div class="col-12 p-4 align-items-center justify-content-center row" style="height:100vh">
            <h3 class="text-center">
                {{-- {{$settings->website_name}} --}}
                <img src="{{ $settings->website_logo() }}" alt="" width="120" height="90">
                {{-- <img src="{{ $settings->website_icon_url }}" alt="" width="30" height="24"> --}}

            </h3>
            <div class="col-12 card">
                <form method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}

                    <div class="col-12 p-0 mb-5" style="width: 550px;max-width: 100%;margin: 0px auto;">
                        <h3 class="my-4 text-center">{{ __('lang.login') }}</h3>
                            <div class="divider"></div>
                    </div>
                    <div class="form-group row pb-3">

                        <div class="col-12">
                            <label for="email" class="col-form-label text-md-end">{{ __('lang.email') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row pb-3">

                        <div class="col-12">
                            <label for="password" class="col-form-label text-md-end">{{ __('lang.password') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row pb-3 text-right">
                        <div class="col-12">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember">
                                {{ __('lang.remember_me') }}
                            </label>
                        </div>
                    </div>


                    <div class="form-group row pb-3 px-5 text-center">
                        <button type="submit" class="btn mainBgColor">
                            {{ __('lang.login') }}
                        </button>
                        <div class="col-md-12">

                            @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('lang.forget_your_password') }}
                                </a>
                            @endif
                        </div>
                        <div class="col-md-12">
                            <a class="btn btn-link" href="{{ route('register') }}">
                                {{ __('lang.Create Account') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
