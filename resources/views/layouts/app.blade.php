<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />


    <!-- BladewindUI-->
    <link href="{{ asset('vendor/bladewind/css/animate.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/bladewind/css/bladewind-ui.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('vendor/bladewind/js/helpers.js') }}"></script>

    <style>
        /******************** BEGIN: Customize BladewindUI CSS *************************/

        .bw-table-filter-bar {
            padding: 0.375rem 0;
            margin: 0.75rem;
            background-color: transparent;
        }

        table {
            table-layout: fixed;
            width: 100%;
            /* Ensures table spans container width */
            border-collapse: collapse;
        }


        td {
            /* Prevent content from spilling out */
            overflow: hidden;
            /* Show ellipsis for truncated content */
            text-overflow: ellipsis;
            /* Prevent text from wrapping */
            white-space: nowrap;
        }

        /* These styles overewrite the color of pagination arrow icons.
         * Noticed the default colors didn't work well */
        /* Styles for light mode */
        @media (prefers-color-scheme: light) {
            .bw-button svg {
                color: #ed1c24;
            }
        }

        /* Styles for dark mode */
        @media (prefers-color-scheme: dark) {
            .bw-button svg {
                color: #ed1c24;
            }
        }

        /******************** END: Customize BladewindUI CSS *************************/
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
    <script>
        //  Hide any BladewindUI .bw-alert element after some time
        const myDiv = document.querySelector('.bw-alert');
        setTimeout(() => {
            if (myDiv !== null) {
                myDiv.style.display = 'none';
            }
        }, 10000);
    </script>
</body>

</html>
