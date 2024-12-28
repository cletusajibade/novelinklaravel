@php
    $currentRouteName = Route::currentRouteName();
    $titlePage = 'Consultation Form';
@endphp

<!DOCTYPE html>
<html lang="en-US">

<head>
    @include('includes/html-head')
    <style id="visarzo-theme-inline-css">
        .page-breadcrumb {
            background-image: url("{{ url('/') }}/uploads/2020/08/page-title.jpg");
        }
    </style>
</head>

<body
    class="home page-template page-template-elementor_header_footer page page-id-7 wp-custom-logo elementor-default elementor-template-full-width elementor-kit-9 elementor-page elementor-page-7 e--ua-blink e--ua-chrome e--ua-webkit"
    data-elementor-device-mode="mobile">

    <!-- HEADER -->
    <x-header :titlePage="$titlePage" :currentRouteName="$currentRouteName" />



    <!-- FOOTER  -->
    <x-footer />
</body>

</html>
