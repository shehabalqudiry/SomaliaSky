<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <link href="{{ asset('assets') }}/css/bootstrap-rtl.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets') }}/css/font-awesome.css" rel="stylesheet" type="text/css"/> --}}

    <link rel="stylesheet" type="text/css" href="{{ asset('css/cust-fonts.css') }}">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/responsive-font.css') }}">


    @if (app()->getLocale() == 'ar')
        <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.rtl.min.css') }}">
        {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous"> --}}
        <style>

        .spanIcon {
                    right: 3% !important;
                }
        </style>
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

        <style>
            .select-selected:after {
                left: 90% !important;
            }

            .select-selected.select-arrow-active:after {
                left: 90% !important;
                border-color: transparent #212529 transparent transparent !important;
            }

            .spanIcon {
                left: 3%;
            }
        </style>
    @endif

    <style>
        a:not([class^="btn"], [class^="navbar-brand"]) {
            color: {{ $settings->main_color }} !important;
        }


        a:not([class^="navbar-brand"]) {
            font-weight: bold;
            /* font-size: 1rem */
        }

        .btn-primary {
            background-color: {{ $settings->main_color }} !important;
            color: #fff !important;
        }

        .mainColor {
            color: {{ $settings->main_color }} !important;
        }

        .mainBgColor {
            background: {{ $settings->main_color }} !important;
            color: #fff !important;
        }


        /* The container must be positioned relative: */
        .custom-select {
            color: {{ $settings->main_color }} !important;
        }

        .custom-select select {
            display: none;
            /*hide original SELECT element: */
        }

        .select-selected {
            color: {{ $settings->main_color }} !important;
        }

        /* Style the arrow inside the select element: */
        .select-selected:after {
            border-color: #212529 transparent transparent transparent;
        }

        .select-selected.select-arrow-active:after {
            border-color: transparent transparent transparent #212529;
        }

        .spanIcon {
            color: {{ $settings->main_color }} !important;
        }

        .is_featured {
            border: 3px solid #ffc800e3;
        }

        /* style the items (options), including the selected item: */
        .select-items div {
            color: #212529;
        }
        .select-items {
            width: 300px !important;
        }

        .select-selected {
            color: #212529;
        }

        .modal-backdrop.show {
            opacity: 0.3 !important;
        }

        .rad14 {
            border-radius: 14px !important;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

        .carousel-inner {
            height: 40vh;
        }

        .carousel-inner img {
            height: 40vh;
        }

        @media (max-width: 720px) {
            .carousel-inner {
                height: 50%;
            }

            .carousel-inner img {
                height: 30vh;
            }
            .spanIcon {
                top: 5% !important;
                {{ app()->getLocale() == "ar" ? "right: 8%" : "left: 8%" }} !important;
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/pace-theme-default.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fancybox.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/font-fileuploader.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/jquery.fileuploader.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/jquery.fileuploader-theme-dragdrop.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/main.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/flag-icons.min.css') }}">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
    {{-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@700&display=swap" rel="stylesheet"> --}}
    <style>
        body {
            /* height: 100%; */
            background: rgb(241, 241, 241);
        }

        .loader-wrapper {
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #ecebeb;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999999999;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: {{ $settings->main_color }};
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #2d6fc9;
        }

        *:not([class^="fa"]) {
            /* font-family: Cairo !important; */
            font-weight: bold
        }
    </style>
    @php
        $page_title = '';
    @endphp
    @include('seo.index')
    @livewireStyles
    @yield('styles')
    @if (auth()->check())
        @php
            if (session('seen_notifications') == null) {
                session(['seen_notifications' => 0]);
            }
            $notifications = auth()
                ->user()
                ->notifications()
                ->orderBy('created_at', 'DESC')
                ->limit(50)
                ->get();
            $unreadNotifications = auth()
                ->user()
                ->unreadNotifications()
                ->count();
        @endphp
    @endif
</head>

<body class="">
    {{-- @yield('after-body') --}}
    @if (flash()->message)
        <div style="position: absolute;z-index: 4444444444444;left: 35px;top: 80px;max-width: calc(100% - 70px);padding: 16px 22px;border-radius: 7px;overflow: hidden;width: 273px;border-right: 8px solid #374b52;background: #2196f3;color: #fff;cursor: pointer;"
            onclick="$(this).slideUp();">
            <span class="fas fa-info-circle"></span> {{ flash()->message }}
        </div>
    @endif
    <div class="col-12 justify-content-end d-flex">
        @if ($errors->any())
            <div class="col-12" style="position: absolute;top: 80px;left: 10px;">
                {!! implode(
                    '',
                    $errors->all(
                        '<div class="alert-click-hide alert alert-danger alert alert-danger col-9 col-xl-3 rounded-0 mb-1" style="position: fixed!important;z-index: 11;opacity:.9;left:25px;cursor:pointer;" onclick="$(this).fadeOut();">:message</div>',
                    ),
                ) !!}
            </div>
        @endif
    </div>
    <div id="app">
        <div class="loader-wrapper mainColor">
            <span class="loader text-center p-5">
                <img src="{{ $settings->website_logo() }}" alt="" class="d-block mt-5" width="100%"
                    height="190">
            </span>
        </div>
        {{-- <main class="position-relative vh-90"> --}}
        @include('layouts.inc.nav')
        {{-- <div class="container"> --}}
        @include('layouts.inc.search')
        @yield('content')
        {{-- </div> --}}
        {{-- </main> --}}

    </div>
    @include('layouts.inc.footer')
    <script src="{{ asset('/js/jquery.min.js') }}"></script>
    <script src="{{ asset('/js/fancybox.umd.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('/js/pace.min.js') }}"></script>

    <script src="{{ asset('/js/select2.min.js') }}"></script>
    <script src="{{ asset('/js/jquery.fileuploader.min.js') }}"></script>
    <script src="{{ asset('/js/validatorjs.min.js') }}"></script>
    <script src="{{ asset('/js/favicon_notification.js') }}"></script>
    <script src="{{ asset('/js/main.js') }}"></script>
    {{-- <script src="{{ asset('/js/app.js') }}"></script> --}}
    <script type="text/javascript">
        $(window).on('load', function() {
            $(".loader-wrapper").fadeOut("slow");
        });

        $(document).ready(function() {
            $('.select2-select').select2();
        });

        var x, i, j, l, ll, selElmnt, a, b, c;
        /* Look for any elements with the class "custom-select": */
        x = document.getElementsByClassName("custom-select");
        l = x.length;
        for (i = 0; i < l; i++) {
            selElmnt = x[i].getElementsByTagName("select")[0];
            ll = selElmnt.length;
            /* For each element, create a new DIV that will act as the selected item: */
            a = document.createElement("DIV");
            a.setAttribute("class", "select-selected");
            a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
            x[i].appendChild(a);
            /* For each element, create a new DIV that will contain the option list: */
            b = document.createElement("DIV");
            b.setAttribute("class", "select-items select-hide");
            for (j = 1; j < ll; j++) {
                /* For each option in the original select element,
                create a new DIV that will act as an option item: */
                c = document.createElement("DIV");
                c.innerHTML = selElmnt.options[j].innerHTML;
                c.addEventListener("click", function(e) {
                    /* When an item is clicked, update the original select box,
                    and the selected item: */
                    var y, i, k, s, h, sl, yl;
                    s = this.parentNode.parentNode.getElementsByTagName("select")[0];
                    sl = s.length;
                    h = this.parentNode.previousSibling;
                    for (i = 0; i < sl; i++) {
                        if (s.options[i].innerHTML == this.innerHTML) {
                            s.selectedIndex = i;
                            h.innerHTML = this.innerHTML;
                            y = this.parentNode.getElementsByClassName("same-as-selected");
                            yl = y.length;
                            for (k = 0; k < yl; k++) {
                                y[k].removeAttribute("class");
                            }
                            this.setAttribute("class", "same-as-selected");
                            break;
                        }
                    }
                    h.click();
                });
                b.appendChild(c);
            }
            x[i].appendChild(b);
            a.addEventListener("click", function(e) {
                /* When the select box is clicked, close any other select boxes,
                and open/close the current select box: */
                e.stopPropagation();
                closeAllSelect(this);
                this.nextSibling.classList.toggle("select-hide");
                this.classList.toggle("select-arrow-active");
            });
        }

        function closeAllSelect(elmnt) {
            /* A function that will close all select boxes in the document,
            except the current select box: */
            var x, y, i, xl, yl, arrNo = [];
            x = document.getElementsByClassName("select-items");
            y = document.getElementsByClassName("select-selected");
            xl = x.length;
            yl = y.length;
            for (i = 0; i < yl; i++) {
                if (elmnt == y[i]) {
                    arrNo.push(i)
                } else {
                    y[i].classList.remove("select-arrow-active");
                }
            }
            for (i = 0; i < xl; i++) {
                if (arrNo.indexOf(i)) {
                    x[i].classList.add("select-hide");
                }
            }
        }

        /* If the user clicks anywhere outside the select box,
        then close all select boxes: */
        document.addEventListener("click", closeAllSelect);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous">
    </script>
    @livewireScripts
    <script>
        window.livewire.on('update', () => {
            document.getElementById('chat').scroll(0, 100000000000000000000000000000);
        });
    </script>
    @include('layouts.scripts')

    @yield('scripts')
    {{-- @stack('scripts') --}}
</body>

</html>
