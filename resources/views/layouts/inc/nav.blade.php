<div class="container-fluid px-3 py-2 font-2 fw-bold">
    <form method="POST" action="{{ route('logout') }}" id="logout-form" class="d-none">@csrf</form>
    <nav class="navbar navbar-expand-lg">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="far fa-bars font-4"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <ul class="col-12 navbar-nav mb-2 mb-lg-0 text-center">
                <li class="nav-item dropdown font-2">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        {{-- <img src="{{ asset(auth()->user()? auth()->user()->getUserAvatar(): 'images/default/avatar.png') }}" --}}
                        <img src="{{ auth()->check()? auth()->user()->getUserAvatar(): asset('images/default/avatar.png') }}"
                            alt="" style="border-radius: 50%" width="40" height="40">
                    </a>
                    @if (auth()->check())
                        <ul class="dropdown-menu text-center">
                            <li class="nav-item pt-2"><a class="nav-link font-2"
                                    href="{{ route('front.profile') }}">{{ auth()->user()->name }}</a></li>
                            <li class="nav-item pt-2"><a class="nav-link font-2"
                                    href="{{ route('front.chat') }}">{{ __('lang.messages') }}
                                    {{-- ({{ auth()->user()->messages }}) --}}
                                </a>
                            </li>
                            @if (auth()->user()->store)
                                <li class="nav-item pt-2"><a class="nav-link font-2"
                                        href="{{ route('front.store.my_store') }}">{{ auth()->user()->store->name }}</a>
                                </li>
                            @else
                                <li class="nav-item pt-2"><a class="nav-link font-2"
                                        href="{{ route('front.store.my_store') }}">{{ __('lang.OpenStore') }}</a></li>
                            @endif
                            <li class="nav-item pt-2"><a class="nav-link font-2" href="#"
                                    onclick="document.getElementById('logout-form').submit();">{{ __('lang.Logout') }}</a>
                            </li>
                        </ul>
                    @endif
                </li>
                <li class="nav-item pt-2">
                    <a class="nav-link font-2 active" href="{{ route('home') }}">{{ __('lang.Go Home') }}</a>
                </li>
                <li class="nav-item pt-2">
                    <a class="nav-link font-2"
                        href="{{ route('front.categories.index') }}">{{ __('lang.Categories') }}</a>
                </li>

                <li class="nav-item dropdown pt-2">
                    <a class="nav-link dropdown-toggle font-2" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <span class="fal fa-language"></span>
                        {{ __('lang.Language') }}
                    </a>
                    <ul class="dropdown-menu text-center">
                        @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            <li>
                                <a class="nav-link" rel="alternate" hreflang="{{ $localeCode }}"
                                    href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                    {{ $properties['native'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
                <li class="nav-item pt-2">
                    <a class="nav-link font-2" href="{{ route('front.stores.index') }}">{{ __('lang.Stores') }}</a>
                </li>

                <li class="nav-item pt-2">
                    <a class="nav-link font-2"
                        href="{{ route('front.announcements.index') }}">{{ __('lang.Announcements') }}</a>
                </li>
                @if (!auth()->check())
                    <li class="nav-item pt-2"><a class="nav-link font-2" href="#" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">{{ __('lang.Login') }}</a></li>
                    <li class="nav-item pt-2"><a class="nav-link font-2"
                            href="{{ route('register') }}">{{ __('lang.Register') }}</a>
                    </li>
                @else
                <div class="btn-group" id="notificationDropdown">

                    <div class="col-12 px-0 d-flex justify-content-center align-items-center btn  "
                        style="width: 55px;height: 55px;" data-bs-toggle="dropdown" aria-expanded="false"
                        id="dropdown-notifications">
                        <span class="fal fa-bell font-3 d-inline-block"
                            style="color: #333;transform: rotate(15deg)"></span>
                        <span
                            style="position: absolute;min-width: 25px;min-height: 25px;
                        @if ($unreadNotifications != 0) display: inline-block;
                        @else
                        display: none; @endif
                        right: 0px;top: 0px;border-radius: 20px;background: #c00;color:#fff;font-size: 14px;"
                            id="dropdown-notifications-icon">{{ $unreadNotifications }}</span>

                    </div>
                    <div class="dropdown-menu py-0 rounded-0 border-0 shadow "
                        style="cursor: auto!important;z-index: 20000;width: 350px;height: 450px;">
                        <div class="col-12 notifications-container" style="height:406px;overflow: auto;">
                            <x-notifications :notifications="$notifications" />
                        </div>
                        <div class="col-12 d-flex border-top">
                            <a href="{{ route('front.notifications.index') }}" class="d-block py-2 px-3 ">
                                <div class="col-12 align-items-center">
                                    <span class="fal fa-bells"></span> عرض كل الإشعارات
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                @endif
                {{-- <li class="nav-item pt-2">
                    <a class="nav-link font-2" href="#">{{ __('lang.Jobs') }}</a>
                </li> --}}
            </ul>
            <div class="d-lg-none mb-5 col-12 text-center">
                {{-- <div> --}}
                <a href="{{ route('front.store.my_store') }}"
                    class="btn btn-dark btn-sm rad14">{{ __('lang.OpenStore') }}</a>
                <a href="{{ route('front.announcements.create') }}"
                    class="btn mainBgColor mx-2 btn-sm rad14">{{ __('lang.AddAnnouncement') }}</a>
                {{-- </div> --}}
            </div>
        </div>
        <div class="d-none d-lg-block mb-lg-0 col-12 col-md-3 text-end">
            {{-- <div> --}}
            <a href="{{ route('front.store.my_store') }}"
                class="btn btn-dark btn-sm rad14">{{ __('lang.OpenStore') }}</a>
            <a href="{{ route('front.announcements.create') }}"
                class="btn mainBgColor mx-2 btn-sm rad14">{{ __('lang.AddAnnouncement') }}</a>
            {{-- </div> --}}
        </div>
        <a class="navbar-brand mainColor mx-3" href="/">
            {{-- <h3>{{$settings->website_name}}</h3> --}}
            <img src="{{ $settings->website_logo() }}" alt="" width="120" height="60">
        </a>
    </nav>

    @if (!auth()->check())
        {{-- Login --}}
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 14px">
                    <form method="POST" class="p-2" action="{{ route('login') }}">
                        @csrf
                        <div class="text-center pb-2 pt-5">
                            <img src="{{ $settings->website_logo() }}" alt="" width="120"
                                height="60">
                            {{-- <img src="{{$settings->website_logo()}}" alt="" width="220" height="50"> --}}
                            <p class="py-1">{{ __('lang.login') }}</p>
                        </div>
                        <div class="form-group row pb-1">

                            <div class="col-12">
                                <label for="email"
                                    class="col-form-label text-md-end">{{ __('lang.email') }}</label>
                                <input id="email" type="email"
                                    class="rad14 form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row pb-1">

                            <div class="col-12">
                                <label for="password"
                                    class="col-form-label text-md-end">{{ __('lang.password') }}</label>
                                <input id="password" type="password"
                                    class="rad14 form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="current-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row pb-3 text-right">
                            <div class="col-12">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('lang.remember_me') }}
                                </label>
                            </div>
                        </div>


                        <div class="form-group row pb-3 px-5 text-center">
                            <button type="submit" class="btn mainBgColor">
                                {{ __('lang.login') }}
                            </button>
                            <div class="col-md-12">

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('lang.forget_your_password') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
