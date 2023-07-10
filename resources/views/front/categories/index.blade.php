@extends('layouts.app')
@section('content')
    <div class="container my-5">
        <section class="row text-center" style="margin: 20px 0">
            <h5 class="text-center text-lg-start">{{ __('lang.Categories') }}</h5>
            @foreach ($categories as $category)
                <a href="{{ route('front.categories.show', $category) }}" class="my-4 border-0 col-6 col-md-4 col-lg-2">
                    <img src="{{ $category->image() }}" alt="{{ $category->title }}" height="170" class="card-img-top rounded-3 mb-3">
                    <h6 class="font-1">{{ $category->title }}</h6>
                </a>
            @endforeach
        </section>
    </div>
@endsection
