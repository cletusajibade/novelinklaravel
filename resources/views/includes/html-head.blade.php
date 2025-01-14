<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="profile" href="https://gmpg.org/xfn/11" />
<title>{{ config('app.name') }}</title>

<!-- BladewindUI-->
<link href="{{ asset('vendor/bladewind/css/animate.min.css') }}" rel="stylesheet" />
<link href="{{ asset('vendor/bladewind/css/bladewind-ui.min.css') }}" rel="stylesheet" />
<script src="{{ asset('vendor/bladewind/js/helpers.js') }}"></script>
<script src="//unpkg.com/alpinejs" defer></script>

<link rel="preload" as="style"
    href="https://fonts.googleapis.com/css?family=Cabin%3A300%2C400%2C400i%2C500%2C500i%2C600%2C600i%2C700%2C700i%2C800%2C800i%2C900%2C900i%7COpen%20Sans%3A300%2C400%2C400i%2C500%2C500i%2C600%2C600i%2C700%2C700i%2C800%2C800i%2C900%2C900i%7CRoboto%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CRoboto%20Slab%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic&#038;subset=latin%2Ccyrillic-ext%2Clatin-ext%2Ccyrillic%2Cgreek-ext%2Cgreek%2Cvietnamese&#038;display=swap" />
<link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Cabin%3A300%2C400%2C400i%2C500%2C500i%2C600%2C600i%2C700%2C700i%2C800%2C800i%2C900%2C900i%7COpen%20Sans%3A300%2C400%2C400i%2C500%2C500i%2C600%2C600i%2C700%2C700i%2C800%2C800i%2C900%2C900i%7CRoboto%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CRoboto%20Slab%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic&#038;subset=latin%2Ccyrillic-ext%2Clatin-ext%2Ccyrillic%2Cgreek-ext%2Cgreek%2Cvietnamese&#038;display=swap"
    media="print" onload="this.media='all'" />
<noscript>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Cabin%3A300%2C400%2C400i%2C500%2C500i%2C600%2C600i%2C700%2C700i%2C800%2C800i%2C900%2C900i%7COpen%20Sans%3A300%2C400%2C400i%2C500%2C500i%2C600%2C600i%2C700%2C700i%2C800%2C800i%2C900%2C900i%7CRoboto%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CRoboto%20Slab%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic&#038;subset=latin%2Ccyrillic-ext%2Clatin-ext%2Ccyrillic%2Cgreek-ext%2Cgreek%2Cvietnamese&#038;display=swap" />
</noscript>
<meta name="robots" content="noindex, noopener" />
<link rel="dns-prefetch" href="//fonts.googleapis.com" />
<link href="https://fonts.gstatic.com" crossorigin rel="preconnect" />
<link rel="alternate" type="application/rss+xml" title="Novelink &raquo; Feed" href="{{ url('/') }}/feed/" />
<link rel="alternate" type="application/rss+xml" title="Novelink &raquo; Comments Feed"
    href="{{ url('/') }}/comments/feed/" />
<style>
    img.wp-smiley,
    img.emoji {
        display: inline !important;
        border: none !important;
        box-shadow: none !important;
        height: 1em !important;
        width: 1em !important;
        margin: 0 0.07em !important;
        vertical-align: -0.1em !important;
        background: none !important;
        padding: 0 !important;
    }
</style>
<link rel="stylesheet" id="wp-block-library-css" href="{{ asset('css/dist/block-library/style.min.css') }}"
    type="text/css" media="all" />
<link rel="stylesheet" id="wp-block-library-css" href="{{ asset('plugins/inlinecss/global-styles-inline-css.css') }}"
    type="text/css" media="all" />
<link rel="stylesheet" id="extendify-gutenberg-patterns-and-templates-utilities-inline-css"
    href="{{ asset('plugins/inlinecss/extendify-gutenberg-patterns-and-templates-utilities.css') }}" type="text/css"
    media="all" />
<link rel="stylesheet" id="changetemplatecolormain-css"
    href="{{ asset('plugins/Color-Change-template/css/change_color_template_main.css') }}" type="text/css"
    media="all" />
<link rel="stylesheet" id="contact-form-7-css" href="{{ asset('plugins/contact-form-7/includes/css/styles.css') }}"
    type="text/css" media="all" />
<link rel="stylesheet" id="elementor-icons-css"
    href="{{ asset('plugins/elementor/assets/lib/eicons/css/elementor-icons.min.css') }}" type="text/css"
    media="all" />
<link rel="stylesheet" id="elementor-frontend-legacy-css"
    href="{{ asset('plugins/elementor/assets/css/frontend-legacy.min.css') }}" type="text/css" media="all" />
<link rel="stylesheet" id="elementor-frontend-css" href="{{ asset('plugins/elementor/assets/css/frontend.min.css') }}"
    type="text/css" media="all" />
<link rel="stylesheet" id="elementor-post-9-css" href="{{ asset('uploads/elementor/css/post-9.css') }}" type="text/css"
    media="all" />
<link rel="stylesheet" id="elementor-post-7-css" href="{{ asset('uploads/elementor/css/post-7.css') }}" type="text/css"
    media="all" />
<link rel="stylesheet" id="font-awesome-all-css" href="{{ asset('themes/novelink/assets/css/font-awesome-all.css') }}"
    type="text/css" media="all" />
<link rel="stylesheet" id="flaticon-css" href="{{ asset('themes/novelink/assets/css/flaticon.css') }}" type="text/css"
    media="all" />
<link rel="stylesheet" id="owl-css" href="{{ asset('themes/novelink/assets/css/owl.css') }}" type="text/css"
    media="all" />
<link rel="stylesheet" id="bootstrap-css" href="{{ asset('themes/novelink/assets/css/bootstrap.css') }}"
    type="text/css" media="all" />
<link rel="stylesheet" id="jquery-fancybox-css"
    href="{{ asset('themes/novelink/assets/css/jquery.fancybox.min.css') }}" type="text/css" media="all" />
<link rel="stylesheet" id="animate-css" href="{{ asset('themes/novelink/assets/css/animate.css') }}"
    type="text/css" media="all" />
<link rel="stylesheet" id="nice-select-css" href="{{ asset('themes/novelink/assets/css/nice-select.css') }}"
    type="text/css" media="all" />
<link rel="stylesheet" id="visarzo-theme-color-css"
    href="{{ asset('themes/novelink/assets/css/color/theme-color.css') }}" type="text/css" media="all" />
<link rel="stylesheet" id="visarzo-switcher-style-css"
    href="{{ asset('themes/novelink/assets/css/switcher-style.css') }}" type="text/css" media="all" />
<link rel="stylesheet" id="visarzo-style-css" href="{{ asset('themes/novelink/style.css') }}" type="text/css"
    media="all" />
<link rel="stylesheet" id="visarzo-responsive-css" href="{{ asset('themes/novelink/assets/css/responsive.css') }}"
    type="text/css" media="all" />
<link rel="stylesheet" id="visarzo-theme-css" href="{{ asset('themes/novelink/assets/css/style-theme.css') }}"
    type="text/css" media="all" />
<link rel="stylesheet" id="elementor-icons-shared-1-css"
    href="{{ asset('plugins/novelink-core/assets/elementor/css/flaticon-style2.css') }}" type="text/css"
    media="all" />
<script type="text/javascript" src="{{ asset('js/jquery/jquery.min.js') }}" id="jquery-core-js"></script>
<script type="text/javascript" src="{{ asset('js/jquery/jquery-migrate.min.js') }}" id="jquery-migrate-js"></script>
<link rel="icon" href="{{ asset('uploads/2020/08/favicon.ico') }}" sizes="32x32" />
<link rel="icon" href="{{ asset('uploads/2020/08/favicon.ico') }}" sizes="192x192" />
<link rel="apple-touch-icon" href="{{ asset('uploads/2020/08/favicon.ico') }}" />
<meta name="msapplication-TileImage" content="{{ asset('uploads/2020/08/favicon.ico') }}" />

<style id="visarzo_options-dynamic-css" title="dynamic-css" class="redux-options-output">
    .blog-breadcrumb {
        background-image: url("{{ asset('uploads/2020/08/page-title-6.jpg') }}");
    }

    .blog-single-breadcrumb {
        background-image: url("{{ asset('uploads/2020/08/page-title-6.jpg') }}");
    }

    .coachings-single-breadcrumb {
        background-image: url("{{ asset('uploads/2020/08/page-title-3.jpg') }}");
    }

    .service-single-breadcrumb {
        background-image: url("{{ asset('uploads/2020/08/page-title-7.jpg') }}");
    }
</style>

<link rel="stylesheet" href="{{ asset('css/zebra_datepicker.css') }}">
