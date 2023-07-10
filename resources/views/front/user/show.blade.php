@extends('layouts.app')
@section('styles')
    <style>
        .search {
            display: none !important;
        }

        .profile-bar {
            color: #eee;
            background: {{ $settings->main_color }};
        }

        .profile-bar .contents {
            background-color: rgba(0, 0, 0, 0.65);
            height: 400px;
            /* position: relative; */
        }



        .profile-bar .contents .image {
            /* position: absolute; */
            border: 7px solid rgba(168, 168, 255, 0.3);
        }

        .profile-bar .contents img {
            /* display: block; */
            width: 80%;
            height: 230px;
            /* color: blue; */
            /* {{ app()->getLocale() == 'ar' ? 'right:10%' : 'left:10%' }} */
        }

        .profile-bar .contents .virefy {
            /* position: absolute; */
            top: 52.5%;
            font-size: 19px;
            border-radius: 50%;
            background: rgb(85, 141, 255);
            /* color: #eeeeee; */
            padding: 5px;
            /* {{ app()->getLocale() == 'ar' ? 'right:17%' : 'left: 17%;' }}; */
        }

        .profile-bar .contents .info {
            text-align: start;
            /* position: absolute; */
            top: 35%;
            {{ app()->getLocale() == 'ar' ? 'right:29%' : 'left:29%' }}
        }


        @media (max-width:1024px) {

            .profile-bar .contents .virefy {
                top: 55.5%;
                {{ app()->getLocale() == 'ar' ? 'right:29.3%' : 'left: 29.3%;' }};
            }

            .profile-bar .contents .info {
                top: 0;
                {{ app()->getLocale() == 'ar' ? 'right:50%' : 'left:50%' }}
            }
        }
    </style>
@endsection
@section('content')
    <div class="profile-bar">
        <div class="contents">
            <div class="container">
                <div class="row">
                    <div class="col-6 col-lg-3 text-center mt-5 mb-2 position-relative">
                        {{-- <img src="" alt="UserAvatar"> --}}
                        <img src="{{ $user->getUserAvatar() }}" alt="" class="image" style="border-radius: 50%">
                        @if ($user->is_featured == 1)
                            <label for="photo" class="position-absolute rounded-circle p-1 pl-5"
                                style="bottom: -6%;{{ app()->getLocale() == 'ar' ? 'right: 42%' : 'left : 40%' }};border:5px solid rgba(168, 168, 255, 0.5)">
                                <span class="far virefy fa-check fw-bold"></span>
                            </label>
                        @endif
                    </div>
                    <div class="info col-6 col-lg-9 mt-5 pt-5">
                        <h3 class="profile-name">{{ $user->name }}</h3>
                        <p class="profile-description">
                            <i class='fas fa-map-marker-alt font-3 me-2'></i>
                            {{ $user->city ? $user->city->country->name : 'no' }} -
                            {{ $user->city ? $user->city->name : 'no' }}
                        </p>
                        <p class="profile-description">
                            {{ $user->description }}
                        </p>
                        {{-- <a href="{{ $user->location }}" class="btn btn-light">
                            <span class="fas fa-location"></span>
                        </a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>


    <livewire:user-job :user="$user" />
    <livewire:user-skill :user="$user" />


    <div class="container my-3">
        <div class="row text-center">
            @forelse($user->announcements->where('type', 0) as $anncment)
                <a href="{{ route('front.announcements.show', $anncment) }}"
                    class="mx-lg-2 rounded-3 mb-4 col-4 col-md-4 col-lg-2 h-hv-50">
                    <img src="{{ $anncment->imagesArray()[0] }}" alt="{{ $anncment->title }}"
                        class="card-img-top {{ $anncment->is_featured > 1 ? 'border-warning border-2' : '' }} {{ $anncment->status == 0 ? 'border-danger border' : '' }} rad14 mb-3"
                        width="100%" height="80%">
                    <h6>{{ $anncment->title }}</h6>
                </a>
            @empty
                <div class="text-center">
                    {{ __('lang.No Current Data') }}
                </div>
            @endforelse
        </div>
    </div>

    {{-- <div class="container my-3 px-5">
        <div class="row">
            <h4>{{ __('lang.Packages') }}</h4>
            @foreach (App\Models\Package::where('price', '>', 0)->latest()->get() as $package)
                <div class="mainBgColor rounded-3 px-3 py-2 my-3 d-flex justify-content-between align-items-center"
                    style="line-height: 1.5">
                    <h5>{{ $package->title }}</h5>
                    <p style="line-height: 1.5">{{ $package->description }}</p>
                    <a target="_blank"
                        href="https://api.whatsapp.com/send?phone={{ $settings->whatsapp_link }}&text={{ 'User : ' . auth()->user()->name . ' Order : ' . $package->title }}"
                        type="button" class="btn btn-light py-1 px-3">{{ __('lang.Send') }}</a>
                </div>
            @endforeach
        </div>
    </div> --}}
@endsection
