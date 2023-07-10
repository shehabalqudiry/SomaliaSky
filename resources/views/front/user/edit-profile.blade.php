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
                <form method="POST" action="{{ route('front.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    <h3 class="my-2">{{ __('lang.register') }}</h3>
                    <div class="row">
                        <div class="col-8">
                            <div class="form-group row pb-3">
                                <div class="col-12 col-md-6">
                                    <label for="name" class="col-form-label text-md-end">{{ __('lang.Name') }}</label>
                                    <input id="name" type="text" placeholder="{{ __('lang.Name') }}" class="form-control rounded-3 @error('name') is-invalid @enderror" name="name" value="{{ $profile->name }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="phone" class="col-form-label text-md-end">{{ __('lang.phone') }}</label>
                                    <input id="phone" type="number" placeholder="962780997333" class="text-start form-control rounded-3 @error('phone') is-invalid @enderror" name="phone" value="{{ $profile->phone }}" required autocomplete="phone" autofocus>
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row pb-3">
                                <div class="col-12 col-md-6">
                                    <label for="email" class="col-form-label text-md-end">{{ __('lang.email') }}</label>
                                    <input id="email" placeholder="{{ __('lang.email') }}" type="email" class="text-start form-control rounded-3 @error('email') is-invalid @enderror" value="{{ $profile->email }}" name="email" required autocomplete="email">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="password" class="col-form-label text-md-end">{{ __('lang.password') }}</label>
                                    <input id="password" type="password" placeholder="************" class="form-control rounded-3 @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row pb-3">
                                @if ($profile->city)
                                <livewire:city :country_id="$profile->city->country_id" :city_id="$profile->city->id" />
                                @else
                                <livewire:city />
                                @endif
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <livewire:upload-photo-with-preview :profile="$profile" />
                        </div>
                        <div class="mt-3 pb-3 px-5 text-end">
                            <button type="submit" class="btn w-25 rounded-5 btn-block mainBgColor">
                                {{ __('lang.Update') }}
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
