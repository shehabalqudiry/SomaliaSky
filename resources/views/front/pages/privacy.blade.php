@extends('layouts.app', ['page_title' => __('lang.Privacy Policy')])
@section('content')
    <div class=" p-0 container">
        <div class="col-12 p-2 p-lg-3 row">
            <div class="col-12 col-lg-8 p-2">
                {!! $settings['privacy_page'] !!}
            </div>
        </div>
    </div>
@endsection
