(function ($) {

    "use strict";

    var three_item_carosel_2 = function ($scope, $) {
        if ($('.three-item-carousel-2').length) {
            $('.three-item-carousel-2').owlCarousel({
                loop: true,
                margin: 0,
                nav: true,
                smartSpeed: 1000,
                autoplay: 500,
                navText: ['<span class="fas fa-angle-left"></span>', '<span class="fas fa-angle-right"></span>'],
                responsive: {
                    0: {
                        items: 1
                    },
                    480: {
                        items: 1
                    },
                    600: {
                        items: 2
                    },
                    800: {
                        items: 2
                    },
                    1024: {
                        items: 3
                    }
                }
            });
        }
    }


    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/novelink_immigration.default', three_item_carosel_2);
    });
})(window.jQuery);