@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 mx-auto text-start px-3 mt-3" style="">
        <div class="col-12 p-4 align-items-center justify-content-center" style="height:100vh">
            <div class="col-12 col-lg-5 mx-auto card p-2 p-lg-4">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="card-body">
                        <h2 class="text-center mb-3">{{ __('lang.forget_your_password') }}</h2>
                        <h6 class="text-center mb-3" style="color: rgb(102, 102, 102); font-size:14px">{{ __('lang.Reset Message') }}</h6>
                        <div class="form-group row">
                            <div class="col-12">
                                <input type="email" class="form-control text-start @error('email') is-invalid @enderror" placeholder="{{ __('lang.Email Address') }}" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn py-2 px-5 btn-primary">
                            {{ __('lang.Send Password Reset Link') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
