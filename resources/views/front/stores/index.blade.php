@extends('layouts.app')

@section('content')
    @include('layouts.inc.category')
    <div class="container mt-5">
        <div class="row">
            {{-- @dd($stores) --}}
            @forelse($stores as $store)
            <div class="col-12 col-lg-4 mb-4">
                    <a href="{{ route('front.stores.show', $store) }}">
                    <div class="card rad14">
                        <div class="row p-2">
                                <div class="col-5 p-0 rad14">
                                    <img src="{{ $store->avatar_image() }}" height="150" class="w-100 rad14"
                                        alt="{{ $store->name }}">
                                </div>
                                <div class="col-7 pt-0">
                                    <div class="card-body">
                                        <h5>{{ $store->name }}</h5>
                                        <p>{{ $store->city->name . ' - ' . $store->city->country->name }}</p>
                                        <p>{{ $store->getCategory() }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                    </div>
                </a>
                </div>

            @empty
                <div class="text-center">
                    {{ __('lang.No Current Data') }}
                </div>
            @endforelse

            <div class="col-12 px-0 py-2 text-center">
                {{ $stores->appends(request()->query())->render() }}
            </div>
        </div>
    </div>
@endsection
