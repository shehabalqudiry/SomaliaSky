@extends('layouts.app')
@section('styles')
    <style>
        .search {
            display: none !important;
        }

        .profile-bar {
            background-image: url({{ $store->cover() }});
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            color: #eee;
        }

        .profile-bar .contents {
            background-color: rgba(0, 0, 0, 0.65);
            height: 350px;
            /* position: relative; */
        }



        .profile-bar .contents .image {
            /* position: absolute; */
            border: 7px solid rgba(168, 168, 255, 0.3);
        }

        .profile-bar .contents img {
            /* display: block; */
            width: 70%;
            /* color: blue; */
            /* {{ app()->getLocale() == 'ar' ? 'right:100%' : 'left:100%' }} */
        }

        .profile-bar .contents .virefy {
            /* position: absolute; */
            top: 30.5%;
            font-size: 19px;
            border-radius: 50%;
            background: rgb(85, 141, 255);
            /* color: #eeeeee; */
            padding: 5px;
            /* {{ app()->getLocale() == 'ar' ? 'right:17%' : 'left: 17%;' }}; */
        }

        @media (max-width:1024px) {
            /*
                    .profile-bar .contents .virefy {
                        top: 55.5%;
                        {{ app()->getLocale() == 'ar' ? 'right:29.3%' : 'left: 29.3%;' }};
                    } */

            .profile-bar .contents .info {
                font-size: 13px;
                /* {{ app()->getLocale() == 'ar' ? 'right:50%' : 'left:50%' }} */
            }
        }

        @media (max-width:360px) {
            /*
                    .profile-bar .contents .virefy {
                        top: 55.5%;
                        {{ app()->getLocale() == 'ar' ? 'right:29.3%' : 'left: 29.3%;' }};
                    } */

            .profile-bar .contents img {
                /* display: block; */
                width: 100%;
                /* color: blue; */
                /* {{ app()->getLocale() == 'ar' ? 'right:100%' : 'left:100%' }} */
            }

            .profile-bar .contents .info {
                font-size: 8px !important;
                /* {{ app()->getLocale() == 'ar' ? 'right:50%' : 'left:50%' }} */
            }
        }
    </style>
@endsection
@section('content')
    <div class="profile-bar">
        <div class="contents">
            <div class="container">
                <div class="row">
                    <div class="col-4 col-lg-4 mt-3 text-center mb-3 position-relative" style="height: 100%">
                        {{-- <img src="" alt="UserAvatar"> --}}
                        <img src="{{ $store->avatar_image() }}" alt="" class="image" style="border-radius: 50%">
                        @if ($store->is_featured == 1)
                            <label for="photo" class="position-absolute rounded-circle p-1 pl-5"
                                style="bottom: -6%;{{ app()->getLocale() == 'ar' ? 'right: 43%' : 'left : 43%' }};border:5px solid rgba(168, 168, 255, 0.5)">
                                <span class="far virefy fa-check fw-bold"></span>
                            </label>
                        @endif
                    </div>
                    <div class="info col-8 col-lg-8 pt-5">
                        <h3 class="profile-name">{{ $store->name }}</h3>
                        <p class="profile-description">
                            <i class='fas fa-map-marker-alt font-3 me-2'></i>
                            {{ $store->city->country->name }} - {{ $store->city->name }}
                        </p>

                        <livewire:rating :store="$store" />
                        <p class="profile-description">
                            {{ $store->description }}
                        </p>
                        <a href="{{ $store->location }}" class="btn btn-light">
                            <span class="fas fa-location"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <div class="row">
            @forelse($store->announcements->where('status', '!=', 0)->where('type', 1) as $anncment)
                <div class="col-12 col-lg-4 mb-4">
                    <div class="card rad14 {{ $anncment->is_featured > 0 ? 'border-warning' : '' }}">
                        <div class="row">
                            <div class="col-6 col-md-5 rad14 py-4">
                                <a href="{{ route('front.announcements.show', $anncment) }}">
                                    <img src="{{ $anncment->imagesArray()[0] ?? env('DEFAULT_IMAGE') }}" class="rad14"
                                        width="100%" height="150vh" alt="{{ $anncment->title }}">
                                </a>
                            </div>
                            <div class="col-6 col-md-7">
                                <div class="card-body">

                                    <p class="text-truncate font-2" title="{{ $anncment->title }}">
                                        <a href="{{ route('front.announcements.show', $anncment) }}">
                                            {{ $anncment->title }}
                                        </a>
                                        @if ($anncment->status == 0)
                                            <button class="btn btn-sm btn-danger">
                                                {{ __('lang.Pending') }}
                                            </button>
                                        @endif
                                    </p>
                                    <h6 class="font-1">
                                        <p class="mb-2 text-truncate">
                                            {{ $anncment->city->name . ' - ' . $anncment->city->country->name }}</p>
                                        <p class="text-truncate ">
                                            {{ ($anncment->category->category ? $anncment->category->category->title : '') . ' - ' . $anncment->category->title }}
                                        </p>
                                    </h6>
                                    <div class="col-12 text-end">
                                        <p>
                                            {{ __('lang.price') . ' ' . $anncment->price . ' ' . ($anncment->currency->name ?? 'SOS') }}
                                        </p>
                                        @if (auth()->check() && auth()->user()->id !== $anncment->user->id)
                                            <a href="{{ route('front.chat', ['user_id' => $anncment->user->id, 'announcement_number' => $anncment->number]) }}"
                                                class="btn m-0 btn-sm rad14 {{ $anncment->is_featured > 0 ? 'btn-warning text-light' : 'mainBgColor' }}">{{ __('lang.Send') }}</a>
                                        @else
                                            <a href="#"
                                                class="btn m-0 btn-sm rad14 {{ $anncment->is_featured > 0 ? 'btn-warning text-light' : 'mainBgColor' }}">{{ __('lang.Send') }}</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @empty
                <div class="text-center">
                    {{ __('lang.No Current Data') }}
                </div>
            @endforelse
        </div>
    </div>
@endsection
