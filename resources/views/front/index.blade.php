@extends('layouts.app')
@section('content')
    <div class="container">
        {{-- Top Banner --}}
        <div class="card my-4">

            <div id="carouselExampleIndicators" class="carousel slide carousel-dark carousel-fade" data-bs-ride="true">
                <div class="carousel-indicators mb-2">
                    @foreach (App\Models\Slider::get() as $slider)
                        <button type="button" data-bs-target="#carouselExampleIndicators"
                            data-bs-slide-to="{{ $loop->index }}" style="border-radius: 50%; height:0px; width: 15px;"
                            class="{{ $loop->first ? 'active' : '' }} mainBgColor" aria-current="true"
                            aria-label="Slide {{ $loop->iteration }}"></button>
                    @endforeach
                </div>
                <div class="carousel-inner">
                    @foreach (App\Models\Slider::get() as $slider)
                        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                            <img src="{{ $slider->image() }}" width="100%" alt="{{ $slider->link }}">
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- Categories Section --}}
        <section class="row justify-content-lg-between text-sm-center">
            <div class="col-12 col-md-4 mb-5">
                <h5 class="text-center text-lg-start">{{ __('lang.Categories') }}</h5>
            </div>
            <div class="col-12 col-md-4 col-lg-2 justify-content-lg-end mb-4">
                <a href="{{ route('front.categories.index') }}" class="btn btn-block w-100 mainBgColor rad14">
                    {{ __('lang.Show All') }}
                    @if (app()->getLocale() == 'ar')
                        <span class="fas fa-arrow-left"></span>
                    @else
                        <span class="fas fa-arrow-right"></span>
                    @endif
                </a>
            </div>
        </section>
        <section class="row text-center" style="margin: 50px 0;">
            @foreach ($categories->where('parent_id', null)->take(6)->get() as $category)
                <a href="{{ route('front.categories.show', $category) }}"
                    class="mb-4 border-0 col-6 col-md-4 col-lg-2 h-hv-50">
                    <img src="{{ $category->image_path }}" alt="{{ $category->title }}"
                        class="card-img-top rounded-3 mb-3" width="100%" height="80%">
                    <h6>{{ $category->title }}</h6>
                </a>
            @endforeach
        </section>
        @forelse ($announcementsCats as $category)
            @if ($is_featured->where('category_id', $category->id)->count() != 0 ||
                $announcements->where('category_id', $category->id)->count() != 0)
                <section class="row justify-content-lg-between text-sm-center">
                    <div class="col-12 col-md-4 mb-4">
                        <h5 class="text-center text-lg-start">
                            <img src="{{ $category->image_path }}" width="60" height="60" alt=""
                                class="rounded-circle">
                            {{ ($category->category ? $category->category->title : '') . ' - ' . $category->title }}
                        </h5>
                    </div>
                    <div class="col-12 col-md-4 col-lg-2">
                        <a href="{{ route('front.categories.show', $category) }}"
                            class="btn btn-block w-100 mainBgColor rad14">
                            {{ __('lang.Show All') }}
                            @if (app()->getLocale() == 'ar')
                                <span class="fas fa-arrow-left"></span>
                            @else
                                <span class="fas fa-arrow-right"></span>
                            @endif
                        </a>
                    </div>
                </section>
            <section class="row py-4">
                {{-- <h6 class="font-4">*</h6> --}}
                @foreach ($is_featured->where('category_id', $category->id)->take(3) as $anncmentf)
                    @if ($anncmentf->getTranslation('title', app()->getLocale()))
                        <div class="col-12 col-lg-4 mb-4">
                            <div class="card rad14 border-warning border-1">
                                <div class="row">
                                    <div class="col-6 col-md-5 rad14 py-4">
                                        <a href="{{ route('front.announcements.show', $anncmentf) }}">
                                            <img src="{{ $anncmentf->imagesArray()[0] ?? env('DEFAULT_IMAGE') }}"
                                                class="rad14" width="100%" height="150vh" alt="{{ $anncmentf->title }}">
                                        </a>
                                    </div>
                                    <div class="col-6 col-md-7">
                                        <div class="card-body">
                                            <p class="text-truncate font-2" title="{{ $anncmentf->title }}">
                                                <a href="{{ route('front.announcements.show', $anncmentf) }}">
                                                    {{ $anncmentf->title }}
                                                </a>
                                            </p>
                                            <h6 class="font-1">
                                                <p class="mb-2 text-truncate">
                                                    {{ $anncmentf->city->name . ' - ' . $anncmentf->city->country->name }}
                                                </p>
                                                <p class="text-truncate ">
                                                    {{ ($anncmentf->category->category ? $anncmentf->category->category->title : '') . ' - ' . $anncmentf->category->title }}
                                                </p>
                                            </h6>
                                            <div class="col-12 text-end">
                                                <p>
                                                    {{ __('lang.price') . ' ' . $anncmentf->price . ' ' . ($anncmentf->currency->name ?? 'rs') }}
                                                </p>
                                                @if (auth()->check() && auth()->user()->id !== $anncmentf->user->id)
                                                    <a href="{{ route('front.chat', ['user_id' => $anncmentf->user->id, 'announcement_number' => $anncmentf->number]) }}"
                                                        class="btn m-0 btn-sm rad14 {{ $anncmentf->is_featured > 0 ? 'btn-warning text-light' : 'mainBgColor' }}">{{ __('lang.Send') }}</a>
                                                @else
                                                    <a href="#"
                                                        class="btn m-0 btn-sm rad14 {{ $anncmentf->is_featured > 0 ? 'btn-warning text-light' : 'mainBgColor' }}">{{ __('lang.Send') }}</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

                @foreach ($announcements->where('category_id', $category->id)->take(9) as $anncment)
                    @if ($anncment->getTranslation('title', app()->getLocale()))
                        <div class="col-12 col-lg-4 mb-4">
                            <div class="card rad14">
                                <div class="row">
                                    <div class="col-6 col-md-5 rad14 py-4">
                                        <a href="{{ route('front.announcements.show', $anncment) }}">
                                            <img src="{{ $anncment->imagesArray()[0] ?? env('DEFAULT_IMAGE') }}"
                                                class="rad14" width="100%" height="150vh" alt="{{ $anncment->title }}">
                                        </a>
                                    </div>
                                    <div class="col-6 col-md-7">
                                        <div class="card-body">
                                            <p class="text-truncate font-2" title="{{ $anncment->title }}">
                                                <a href="{{ route('front.announcements.show', $anncment) }}">
                                                    {{ $anncment->title }}
                                                </a>
                                            </p>
                                            <h6 class="font-1">
                                                <p class="mb-2 text-truncate">
                                                    {{ $anncment->city->name . ' - ' . $anncment->city->country->name }}
                                                </p>
                                                <p class="text-truncate ">
                                                    {{ ($anncment->category->category ? $anncment->category->category->title : '') . ' - ' . $anncment->category->title }}
                                                </p>
                                            </h6>
                                            <div class="col-12 text-end">
                                                <p>
                                                    {{ __('lang.price') . ' ' . $anncment->price . ' ' . ($anncment->currency->name ?? 'rs') }}
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
                    @endif
                @endforeach
            </section>
            @endif
        @empty
            <section class="h-100">
                <h4>{{ __('lang.No Data') }}</h4>
            </section>
        @endforelse
    </div>
@endsection
