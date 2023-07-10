@extends('layouts.app')

@section('content')
    @include('layouts.inc.category')
    @include('layouts.inc.filter')
    <div class="container mt-5">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="row">
                    @forelse($announcements as $anncment)
                        @if ($anncment->getTranslation('title', app()->getLocale()))
                            <div class="col-12 col-lg-4 mb-4">
                                <div class="card rad14 {{ $anncment->is_featured > 0 ? 'border-warning border-1' : '' }}">
                                    <div class="row">
                                        <div class="col-6 col-md-5 rad14 py-4">
                                            <a href="{{ route('front.announcements.show', $anncment) }}">
                                                <img src="{{ $anncment->imagesArray()[0] ?? env('DEFAULT_IMAGE') }}"
                                                    class="rad14" width="100%" height="150vh"
                                                    alt="{{ $anncment->title }}">
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
                                                        {{ ($anncment->city->name ?? '') . ' - ' . ($anncment->city->country->name ?? '') }}
                                                    </p>
                                                    <p class="text-truncate ">
                                                        @if ($anncment->category)
                                                            {{ ($anncment->category->category->title ?? '') . ' - ' . $anncment->category->title ?? '' }}
                                                        @endif
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
                        @endif
                        {{-- <div class="col-12 col-lg-4 mb-4">
                            <div
                                class="card rad14 {{ $anncment->is_featured > 0 ? 'border-warning border-3' : '' }} {{ $anncment->status == 0 ? 'border-danger border-3' : '' }}">
                                <div class="row p-2">
                                    <div class="col-6 p-0 rad14">
                                        <img src="{{ $anncment->imagesArray()[0] }}" height="200" class="w-100 rad14"
                                            alt="{{ $anncment->title }}">
                                    </div>
                                    <div class="col-6 pt-0">
                                        <div class="card-body">
                                            @if ($anncment->status == 0)
                                                <button class="btn btn-danger">
                                                    {{ __('lang.Pending') }}
                                                </button>
                                            @endif
                                            <h5 class="text-truncate" title="{{ $anncment->title }}"><a
                                                    href="{{ route('front.announcements.show', $anncment) }}">{{ $anncment->title }}</a>
                                            </h5>
                                            <p>{{ $anncment->city->name . ' - ' . $anncment->city->country->name }}</p>
                                            <p>{{ ($anncment->category->category ? $anncment->category->category->title : '') . ' - ' . $anncment->category->title }}
                                            </p>
                                        </div>
                                        <div class="col-12 pt-0 mt-0 text-end">
                                            <h6 class="{{ $anncment->is_featured > 0 ? 'text-warning' : 'mainColor' }}">
                                                {{ __('lang.price') . ' ' . $anncment->price . ' ' . ($anncment->currency->name ?? 'rs') }}
                                            </h6>
                                            @if (auth()->check() && auth()->user()->id !== $anncment->user->id)
                                            <a href="{{ route('front.chat', ['user_id' => $anncment->user->id, 'announcement_number' => $anncment->number]) }}"
                                                class="btn m-0 btn-sm w-50 rad14 {{ $anncment->is_featured > 0 ? 'btn-warning text-light' : 'mainBgColor' }}">{{ __('lang.Send') }}</a>
                                            @else
                                            <a href="#"
                                                class="btn m-0 btn-sm w-50 rad14 {{ $anncment->is_featured > 0 ? 'btn-warning text-light' : 'mainBgColor' }}">{{ __('lang.Send') }}</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div> --}}
                    @empty
                        <div class="text-center">
                            {{ __('lang.No Current Data') }}
                        </div>
                    @endforelse
                </div>
            </div>
            {{-- <div class="col-12 col-lg-2 mb-4">
                @php
                    $ad = App\Models\Announcement::where('is_featured', '>', 8)
                        ->inRandomOrder()
                        ->first();
                @endphp
                <img src="{{ $ad ? $ad->ImagesArray()[0] : asset('images/default/logo.png') }}" width="100%"
                    height="720" alt="">
            </div> --}}
            <div class="col-12 px-0 py-2 text-center">
                {{ $announcements->appends(request()->query())->render() }}
            </div>
        </div>
    </div>
@endsection
