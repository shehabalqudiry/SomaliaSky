@extends('layouts.app', ['page_image' => $announcement->imagesArray()[0] ?? null])
@section('styles')
    <style>
        .search {
            display: none !important;
        }

        .social-btn-sp #social-links {
            margin: 0 auto;
        }

        .social-btn-sp #social-links ul li {
            display: inline-block;
        }

        .social-btn-sp #social-links ul li a {
            padding: 15px;
            margin: 1px;
            font-size: 30px;
        }

        table #social-links {
            display: inline-table;
        }

        table #social-links ul li {
            display: inline;
        }

        table #social-links ul li a {
            padding: 5px;
            margin: 1px;
            font-size: 15px;
            background: #e3e3ea;
        }
        #fullpage {
            display: none;
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #1d1d1d81;
            justify-content: center;
            align-items: center;
            z-index: 9999999999;
            background-size: contain;
            background-repeat: no-repeat no-repeat;
            background-position: center center;
        }
    </style>
@endsection
@section('content')
    <div class="container py-3 px-0" onload="getPics()">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('front.categories.index') }}">{{ __('lang.Categories') }}</a>
                </li>
                <li class="breadcrumb-item"><a
                        href="{{ route('front.categories.index') }}">{{ $announcement->category->category ? $announcement->category->category->title : '' }}</a>
                </li>
                <li class="breadcrumb-item"><a
                        href="{{ route('front.categories.index') }}">{{ $announcement->category->title ?? '' }}</a></li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-12 col-lg-9 mb-4">
                <div class="row">
                    <div class="col-12 col-md-6" style="overflow: hidden">
                        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                @foreach ($announcement->imagesArray() as $img)
                                    <button type="button" data-bs-target="#carouselExampleIndicators"
                                        data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}"
                                        aria-current="true" aria-label="Slide 1"></button>
                                @endforeach
                            </div>
                            <div class="carousel-inner">
                                @foreach ($announcement->imagesArray() as $img)
                                    <div class="carousel-item {{ $loop->first ? 'active' : '' }} rad14 gallery">
                                        <img src="{{ $img }}" class="d-block w-100 rad14" height="250"
                                            alt="{{ $announcement->title }}">
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 p-4" style="overflow: hidden">
                        <h4>{{ $announcement->title }}</h5>
                            <p class="">{{ $announcement->created_at->diffforhumans() }}</p>
                            <p class="">
                                {{ __('lang.Address') . ' : ' . ($announcement->city->name ?? '') . ' - ' . ($announcement->city->country->name ?? '') }}
                            </p>
                            <h5 class="">
                                {{ __('lang.price') . ' ' . $announcement->price . ' ' . ($announcement->currency->name ?? 'rs') }}
                            </h5>
                    </div>
                </div>
                <div class="row mt-4 py-3 text-secondary" style="overflow: hidden">
                    <div class="col-8">
                        <p>{{ $announcement->description }}</p>
                    </div>
                    @if (count($announcement->AnnouncementAttribute))
                        <div class="col-6">
                            <h4>{{ __('lang.attributes') }}</h4>
                            <ol class="list-group list-group-numbered boderd-0">
                                @foreach ($announcement->AnnouncementAttribute as $attr)
                                    <li
                                        class="boderd-0 bg-transparent list-group-item d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold">{{ $attr->attributeRelation->name ?? '' }}</div>
                                            {{ $attr->value ?? '' }}
                                        </div>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-12 col-lg-3 mb-4">
                <div class="card text-center p-3 rad14">
                    {{-- @dd($announcement->user) --}}
                    @if ($announcement->type == 1)
                        <img src="{{ $announcement->store->avatar_image() }}" alt="{{ $announcement->title }}"
                            width="120" height="120" class="mx-auto rounded-circle card-ipg-top">
                        <div class="card-body">
                            <a href="{{ route('front.stores.show', $announcement->store->slug) }}">
                                <h4 class="card-title">
                                    {{ $announcement->store->name }}
                                </h4>
                            </a>
                            <livewire:rating :store="$announcement->store" />
                            <a href="@if (auth()->check() && $announcement->user->id !== auth()->user()->id) {{ route('front.chat', ['user_id' => $announcement->user->id, 'announcement_number' => $announcement->number]) }} @endif"
                                class="btn mainBgColor btn-block mb-2 w-100 rad14">{{ __('lang.Send') }}</a>
                            <a href="#"
                                class="btn btn-block w-100 btn-dark rad14">{{ __('lang.Share :name', ['name' => __('lang.Announcement')]) }}</a>
                        </div>
                    @else
                        <img src="{{ $announcement->user->getUserAvatar() }}" alt="{{ $announcement->title }}"
                            width="120" height="120" class="mx-auto rounded-circle card-ipg-top">
                        <div class="card-body">
                            <a href="{{ route('front.users.show', $announcement->user) }}">
                                <h4 class="card-title">
                                    {{ $announcement->user->name ?? '' }}
                                </h4>
                            </a>
                            <livewire:rating :store="$announcement->user" />
                            <a href="@if (auth()->check() && $announcement->user->id !== auth()->user()->id) {{ route('front.chat', ['user_id' => $announcement->user->id, 'announcement_number' => $announcement->number]) }} @endif"
                                class="btn mainBgColor btn-block mb-2 w-100 rad14">{{ __('lang.Send') }}</a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#ModalShare"
                                class="btn btn-block w-100 btn-dark rad14">{{ __('lang.Share :name', ['name' => __('lang.Announcement')]) }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <h5 class="text-start my-3">{{ __('lang.related :resource', ['resource' => __('lang.Announcements')]) }}</h5>
            @foreach ($relatedAds as $ad)
                <a href="{{ route('front.announcements.show', $ad) }}"
                    class="card bg-transparent rad14 mx-auto col-4 col-lg-2 border-0">
                    <img src="{{ $ad->imagesArray()[0] }}" alt="{{ $ad->title }}"
                        class="card-img-top rounded-3 mb-3 {{ $ad->is_featured > 1 ? 'border-warning border-3' : 'border-0' }}"
                        height="160">
                    <div class="text-center card-body">
                        <h6 class="text-truncate" title="{{ $ad->title }}"
                            class="font-1 {{ $ad->is_featured > 1 ? 'text-warning' : '' }}">{{ $ad->title }}</h6>
                    </div>
                </a>
            @endforeach
        </div>
    </div>


    <div class="modal fade" id="ModalShare" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 14px">
                <div class="container my-4">
                    <div class="social-btn-sp text-center">
                        <div id="social-links">
                            <ul>
                                <li>
                                    <a href="{{ $shareButtons['facebook'] }}"
                                        class="social-button " id="" title="" rel=""><span
                                            class="fab fa-facebook-square"></span></a></li>
                                <li><a href="{{ $shareButtons['twitter'] }}"
                                        class="social-button " id="" title="" rel=""><span
                                            class="fab fa-twitter"></span></a></li>
                                <li><a target="_blank"
                                        href="{{ $shareButtons['telegram'] }}"
                                        class="social-button " id="" title="" rel=""><span
                                            class="fab fa-telegram"></span></a></li>
                                <li><a target="_blank"
                                        href="{{ $shareButtons['whatsapp'] }}"
                                        class="social-button " id="" title="" rel=""><span
                                            class="fab fa-whatsapp"></span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="fullpage" onclick="this.style.display='none';">

    </div>
    @endsection
    @section('scripts')
        <script>
            function getPics() {} //just for this demo
            const imgs = document.querySelectorAll('.gallery img');
            const fullPage = document.querySelector('#fullpage');

            imgs.forEach(img => {
                img.addEventListener('click', function() {
                    fullPage.style.backgroundImage = 'url(' + img.src + ')';
                    fullPage.style.display = 'block';
                });
            });
        </script>
    @endsection
