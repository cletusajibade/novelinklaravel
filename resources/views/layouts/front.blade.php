{{--
    NOTE:
    - This layout template is the view for the FrontLayout component used by the frontend pages.
    - The $pageTitle, $currentRouteName and $bgUrl variables availlable here are the public properties of the component.
    - They are automatically made available here when the component is rendered or used anywhere.
    - Remember they are passed to the component as html attributes in kebab-case wherever the component is rendered or used.
    - e.g: <x-front-layout page-title="VALUE" current-route-name="VALUE" bg-url="VALUE">
--}}
<!DOCTYPE html>
<html lang="en-US">

<head>
    @include('includes/html-head')
    <style id="visarzo-theme-inline-css">
        .page-breadcrumb {
            background-image: url("{{ url('/') }}/uploads/{{ $bgUrl }}");
        }
    </style>
</head>

<body
    class="home page-template page-template-elementor_header_footer page page-id-7 wp-custom-logo elementor-default elementor-template-full-width elementor-kit-9 elementor-page elementor-page-7 e--ua-blink e--ua-chrome e--ua-webkit"
    data-elementor-device-mode="mobile">

    <!-- HEADER -->
    <x-header :page-title="$pageTitle" :current-route-name="$currentRouteName" />

    {{ $slot }}

    <!-- FOOTER  -->
    <x-footer />
</body>

</html>
