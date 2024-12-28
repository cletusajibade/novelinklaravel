(function ($) {

    "use strict";

    var bannerslider = function ($scope, $) {

        if ($('.banner-carousel').length) {

            let dataSlider = $('.banner-carousel').data('slider');

            $('.banner-carousel').owlCarousel({
                margin: 0,
                animateOut: 'fadeOut',
                animateIn: 'fadeIn',
                active: true,
                autoplay: JSON.parse(dataSlider.autoplay),
                autoplayTimeout: parseInt(dataSlider.autoplayTimeout),
                mouseDrag: JSON.parse(dataSlider.mouseDrag),
                nav: JSON.parse(dataSlider.nav),
                loop: JSON.parse(dataSlider.loop),
                navText: ['<span class="fa fa-angle-left"></span>', '<span class="fa fa-angle-right"></span>'],
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 1
                    },
                    800: {
                        items: 1
                    },
                    1024: {
                        items: 1
                    }
                }
            });
        }
    }

    //Elementor JS Hooks
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/novelink_banner.default', bannerslider);


    });


})(window.jQuery);