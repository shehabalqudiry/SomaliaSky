@extends('layouts.app')
@section('content')
    {{-- @isset($subcats)
        <div class="container px-5 pt-4">
            <h5 class="text-center text-lg-start font-5">{{ $category->title }}</h5>
        </div>
        <div class="container-fluid my-3">
            <section class="row text-center mx-auto">
                @foreach ($subcats as $cat)
                    <a href="{{ route('front.categories.show', $cat) }}" class="mb-4 p-0 w-50">
                        <img src="{{ $cat->image() }}" alt="{{ $cat->title }}" height="280" class="p-0 card-img-top">
                        <h6 class="font-3 py-2 px-0 mainBgColor">{{ $cat->title }}</h6>
                    </a>
                @endforeach
            </section>
        </div>
    @endisset --}}
    @isset($announcements)
        <div class="container-fluid mainBgColor px-5 py-3 my-3"
            style="border-radius: 5px 20px 0 0;padding-start: 20px    border-radius: 5px 20px 0px 0px; padding: 10px 150px 10px !important;">
            <h5 class="text-center text-lg-start font-4">{{ $category->title }}</h5>
        </div>
        @include('layouts.inc.category')
        @include('layouts.inc.filter')
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-lg-12 mb-4">
                    <div class="row">
                        @forelse($announcements as $anncment)
                            @if ($anncment->getTranslation('title', app()->getLocale()))

                            <div class="col-12 col-lg-4 mb-4">
                                <div
                                    class="card rad14 {{ $anncment->is_featured > 1 ? 'border-warning border-3' : '' }} {{ $anncment->status == 0 ? 'border-danger border-3' : '' }}">
                                    <div class="row p-2">
                                        <div class="col-6 p-0 rad14">
                                            <a href="{{ route('front.announcements.show', $anncment) }}">
                                                <img src="{{ $anncment->imagesArray()[0] }}" height="200" class="w-100 rad14"
                                                    alt="{{ $anncment->title }}">
                                            </a>
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
                                                <h6 class="{{ $anncment->is_featured > 1 ? 'text-warning' : 'mainColor' }}">
                                                    {{ __('lang.price') . ' ' . $anncment->price . ' ' . ($anncment->currency->name ?? 'rs') }}
                                                </h6>
                                                @if (auth()->check() && auth()->user()->id !== $anncment->user->id)
                                                    <a href="{{ route('front.chat', ['user_id' => $anncment->user->id, 'announcement_number' => $anncment->number]) }}"
                                                        class="btn m-0 btn-sm w-50 rad14 {{ $anncment->is_featured > 0 ? 'btn-warning text-light' : 'mainBgColor' }}">مراسلة</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endif
                        @empty
                            <div class="text-center">
                                {{ __('lang.No Current Data') }}
                            </div>
                        @endforelse
                    </div>
                </div>
                {{-- <div class="col-12 col-lg-2 mb-4">
                    @php
                        $ad = App\Models\Announcement::where('is_featured', '>', 6)
                            ->inRandomOrder()
                            ->first();
                    @endphp
                    <img src="{{ $ad ? $ad->ImagesArray()[0] : asset('images/icons/annonce.png') }}"
                        width="100%" height="720" alt="">
                </div> --}}
                <div class="col-12 px-0 py-2 text-center">
                    {{ $announcements->appends(request()->query())->render() }}
                </div>
            </div>
        </div>
    @endisset
@endsection
