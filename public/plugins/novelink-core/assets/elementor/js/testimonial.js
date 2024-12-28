(function ($) {

    "use strict";

    var two_column = function ($scope, $) {
        if ($('.two-column-carousel').length) {
            $('.two-column-carousel').owlCarousel({
                loop: true,
                margin: 30,
                nav: true,
                smartSpeed: 3000,
                autoplay: 4000,
                navText: ['<span class="fas fa-algle-left"></span>', '<span class="fas fa-algle-left-right"></span>'],
                responsive: {
                    0: {
                        items: 1
                    },
                    480: {
                        items: 1
                    },
                    600: {
                        items: 1
                    },
                    800: {
                        items: 2
                    },
                    1024: {
                        items: 2
                    }
                }
            });
        }
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/novelink_testimonial.default', two_column);
    });
})(window.jQuery);