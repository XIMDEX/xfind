$(document).ready(function () {
    $(".home-carousel").owlCarousel({
        items: 1,
        responsive: {
            0: {
                loop: true
            },
            1024: {
                loop: false
            }
        },
        nav: true,
        navText: ['<i class="fas fa-angle-left"></i>', '<i class="fas fa-angle-right"></i>']
    });

    $(".highlights-carousel").owlCarousel({
        responsiveClass: true,
        responsive: {
            0: {
                items: 1,
                loop: true
            },
            1024: {
                items: 2,
                loop: false
            },
            1440: {
                items: 3
            }
        },
        nav: true,
        navText: ['<i class="fas fa-angle-left"></i>', '<i class="fas fa-angle-right"></i>']
    });

    $(".carousel").owlCarousel({
        responsiveClass: true,
        responsive: {
            0: {
                items: 1,
                loop: true
            },
            600: {
                items: 2
            },
            1024: {
                items: 3,
                loop: false
            },
            1440: {
                items: 4
            }
        },
        nav: true,
        navText: ['<i class="fas fa-angle-left"></i>', '<i class="fas fa-angle-right"></i>']
    });
});