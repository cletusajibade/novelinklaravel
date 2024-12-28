(function ($) {

    "use strict";

    var training = function ($scope, $) {
        if ($('.four-item-carousel').length) {
            $('.four-item-carousel').owlCarousel({
                loop: true,
                margin: 30,
                nav: true,
                smartSpeed: 500,
                autoplay: 5000,
                navText: ['<span class="fas fa-angle-left"></span>', '<span class="fas fa-angle-right"></span>'],
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 2
                    },
                    800: {
                        items: 3
                    },
                    1024: {
                        items: 3
                    },
                    1200: {
                        items: 4
                    }
                }
            });
        }
    }

    var three_item_carosel = function ($scope, $) {
        if ($('.three-item-carousel').length) {
            $('.three-item-carousel').owlCarousel({
                loop: true,
                margin: 30,
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
        elementorFrontend.hooks.addAction('frontend/element_ready/novelink_training.default', training);
        elementorFrontend.hooks.addAction('frontend/element_ready/novelink_sevice.default', three_item_carosel);
        elementorFrontend.hooks.addAction('frontend/element_ready/novelink_blog.default', three_item_carosel);
        elementorFrontend.hooks.addAction('frontend/element_ready/novelink_feature.default', three_item_carosel);
        elementorFrontend.hooks.addAction('frontend/element_ready/novelink_funfact.default', three_item_carosel);
        elementorFrontend.hooks.addAction('frontend/element_ready/novelink_sevice.default', training);
        elementorFrontend.hooks.addAction('frontend/element_ready/novelink_client.default', training);
        elementorFrontend.hooks.addAction('frontend/element_ready/novelink_offer.default', training);
        elementorFrontend.hooks.addAction('frontend/element_ready/novelink_team.default', training);
    });
})(window.jQuery);