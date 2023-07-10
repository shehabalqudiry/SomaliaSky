@extends('layouts.app',['page_title'=>"تواصل معنا"])
@section('styles')
<style>
    .search{
        display: none;
    }
</style>
@endsection
@section('content')
<div class="col-12 p-0">
    <div class=" p-0 container">
        <div class="col-12 p-2 p-lg-3 row">
            <div class="col-12 p-2">
                <div class="col-12 p-0 font-3 text-center mainColor">
                    <span class=""></span> {{ __('lang.contact') }}
                </div>
                {{-- <div class="col-12 p-0 mt-1" style="opacity: .7;">
                    معلومات عنا
                </div> --}}
            </div>
            <div class="col-12 col-lg-6 p-0 mx-auto">
                <div style="max-width: 100%;text-align: justify;" class="mx-auto p-0 font-2 naskh">
                    <form class="" method="POST" action="{{route('contact-post')}}" id="contact-form">
                        {{-- <input type="hidden" name="recaptcha" id="recaptcha">  --}}
                        @csrf
                        <div class="col-12 px-0 py-3">
                            <div class="col-12">
                                <input type="text" name="name" class="form-control rounded-0" placeholder="{{ __('lang.name') }}" required="" min="3" max="255" value="">
                            </div>
                        </div>
                        <div class="col-12 px-0 py-3">
                            <div class="col-12">
                                <input type="email" name="email" class="form-control rounded-0" style="text-align: {{ app()->getLocale() == 'ar' ? "right" : 'left' }} !important" placeholder="{{ __('lang.Email Address') }}" required="" value="">
                            </div>
                        </div>
                        <div class="col-12 px-0 py-3">
                            <div class="col-12">
                                <input type="text" name="phone" class="form-control rounded-0" placeholder="{{ __('lang.phone') }}" required="" min="99999999" max="9999999999999999" value="">
                            </div>
                        </div>
                        <div class="col-12 px-0 py-3">
                            <div class="col-12">
                                <textarea class="form-control rounded-0" name="message" style="min-height:200px" placeholder="{{ __('lang.contact') }}" required="" minlength="3" maxlength="1000"></textarea>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button class="btn mainBgColor w-50 p-2 font-2 rad14 btn-block" type="submit" >{{ __('lang.Send') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
