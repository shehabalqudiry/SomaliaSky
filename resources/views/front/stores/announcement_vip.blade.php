@extends('layouts.app')
@section('styles')
    <style>
        .search {
            display: none;
        }

        .full-height {
            height: 80vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }
    </style>
@endsection
@section('content')
    <div class="d-flex flex-column justify-content-center align-items-center full-height position-ref">
        <div class="my-2">
            <span class="fad fa-bullhorn mainColor" style="font-size: 120px"></span>
        </div>
        <h3 class="my-3 mainColor">
            {{ __('lang.Announcement Publiched') }}
        </h3>

        <h5 class="my-3 font-2">
            {{ __('lang.Announcement VIP') }}
        </h5>
        <a target="_blank" href="https://api.whatsapp.com/send?phone={{ $settings->whatsapp_link }}&text={{ __('lang.Announcement VIP') . ' ' . __("lang.Announcement Code"). ' : ' . $announcement->number }}" class="btn btn-block w-25 my-2 py-2 rad14" style="background: rgb(44, 190, 44);color:cornsilk">{{ __("lang.what'sapp") }}</a>
        <h3 class="my-3">
            <a class="mainColor" href="{{ route('front.announcements.show', $announcement) }}">
                {{ __("lang.Announcement Code"). ' : #' . $announcement->number }}
            </a>
        </h3>
    </div>
@endsection
