$(document).ready(function () {

    $('#news').owlCarousel({

        autoplay: true,
        rtl: true,
        loop: true,
        nav: true,
        margin: 15,
        dots: false,
        autoplayHoverPause: true,
        transitionStyle: true,
        autoplayTimeout: 1000,
        smartSpeed: 1000,
        navText: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 3
            }
        }
    });


    $('#fatwas').owlCarousel({

        autoplay: true,
        rtl: true,
        loop: true,
        nav: true,
        dots: false,
        margin: 20,
        transitionStyle: true,
        autoplayTimeout: 6000,
        smartSpeed: 1000,
        navText: [
            "<i class='fa fa-arrow-right'></i>",
            "<i class='fa fa-arrow-left'></i>"
        ],
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 3
            }
        }
    });



   $('#related').owlCarousel({

        autoplay: true,
        rtl: true,
        loop: true,
        nav: true,
        dots: false,
        margin: 20,
        transitionStyle: true,
        autoplayTimeout: 6000,
        smartSpeed: 1000,
        navText: [
            "<i class='fa fa-arrow-right'></i>",
            "<i class='fa fa-arrow-left'></i>"
        ],
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 3
            }
        }
    });

    $('#client').owlCarousel({

        autoplay: true,
        rtl: true,
        loop: true,
        nav: true,
        dots: true,
        margin: 20,
        transitionStyle: true,
        autoplayTimeout: 6000,
        smartSpeed: 1000,
        navText: false,
        responsive: {
            0: {
                items: 2
            },
            600: {
                items: 2
            },
            1000: {
                items: 4
            }
        }
    });


    $('#test').owlCarousel({

        autoplay: true,
        rtl: true,
        loop: true,
        nav: true,
        dots: true,
        margin: 20,
        transitionStyle: true,
        autoplayTimeout: 6000,
        smartSpeed: 1000,
        navText: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 3
            }
        }
    });
    $('#big').owlCarousel({
        autoplay: true,
        rtl: true,
        loop: true,
        URLhashListener: true,
        autoplayHoverPause: true,
        margin: 20,
        nav: false,
        dots: false,
        transitionStyle: true,
        autoplayTimeout: 10000,
        navText: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    });

    $('#product').owlCarousel({
        autoplay: true,
        rtl: true,
        loop: true,
        URLhashListener: true,
        autoplayHoverPause: true,
        margin: 20,
        nav: false,
        dots: false,
        transitionStyle: true,
        autoplayTimeout: 10000,
        navText: false,
        responsive: {
            0: {
                items: 3
            },
            600: {
                items: 3
            },
            1000: {
                items: 4
            }
        }
    });


    $('.content .categories .title').click(function () {
        $('.content .categories ul').slideToggle(1000);

    });

    $('.google-adsing #btn1').click(function () {
        $('.google-adsing').hide(500);

    });

    $('#nav-men').click(function () {
        $('#s-nav').addClass('nav-go');
        $('#sa').show();
    });

    $('#overlay,.men-cl').click(function () {
        $('#s-nav').removeClass('nav-go');
    });

    $('#sa').on('click', function () {
        $('#s-nav').removeClass('nav-go');
        $('.nav-go').hide();
        $('#sa').hide();

    });



});








