{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Error</title>
    <!-- Load Bundled Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }

        .error-container {
            max-width: 600px;
            background: #fff;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .error-header {
            font-size: 24px;
            color: #dc3545;
            margin-bottom: 10px;
        }

        .error-message {
            font-size: 16px;
            color: #333;
            margin-bottom: 20px;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .contact-link,
        .home-link {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            font-size: 14px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .contact-link {
            background-color: #007bff;
        }

        .contact-link:hover {
            background-color: #0056b3;
        }

        .home-link {
            background-color: #28a745;
        }

        .home-link:hover {
            background-color: #218838;
        }

        .home-link svg {
            margin-right: 8px;
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-header">
            @if (isset($first_name))
                Hello, {{$first_name}}
            @else
                Booking Info
            @endif
        </div>
        <div class="error-message">
           You already have a pending or confirmed appointment. You may reschedule or cancel. Please, contact <strong> {{config('app.name')}} ({{env('REPLY_TO_ADDRESS')}}) </strong> for next steps.
        </div>
        <div class="button-container">
            <a href="{{ route('home') }}" class="home-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 1-.5.5H2.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 0 1 .708.708L2.707 7.5H14.5A.5.5 0 0 1 15 8z" />
                </svg>
                Back to Home
            </a>
             @if (isset($reschedule_link))
               <a href="{{ $reschedule_link }}" class="reschedule-link">
                    Reschedule
                </a>
             @endif
        </div>
    </div>
</body>

</html> --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Error</title>
    <!-- Load Bundled Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }

        .error-container {
            max-width: 600px;
            background: #fff;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .error-header {
            font-size: 24px;
            color: #dc3545;
            margin-bottom: 10px;
        }

        .error-message {
            font-size: 16px;
            color: #333;
            margin-bottom: 20px;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .contact-link,
        .home-link,
        .reschedule-link {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            font-size: 14px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .contact-link {
            background-color: #007bff;
        }

        .contact-link:hover {
            background-color: #0056b3;
        }

        .home-link {
            background-color: #28a745;
        }

        .home-link:hover {
            background-color: #218838;
        }

        .home-link svg {
            margin-right: 8px;
        }

        .reschedule-link {
            background-color: #007bff;
        }

        .reschedule-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-header">
            @if (isset($first_name))
                Hello, {{$first_name}}
            @else
                Booking Info
            @endif
        </div>
        <div class="error-message">
           You already have a pending or confirmed appointment. You may reschedule or cancel. Please, contact <strong> {{config('app.name')}} ({{env('REPLY_TO_ADDRESS')}}) </strong> for next steps.
        </div>
        <div class="button-container">
            <a href="{{ route('home') }}" class="home-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 1-.5.5H2.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 0 1 .708.708L2.707 7.5H14.5A.5.5 0 0 1 15 8z" />
                </svg>
                Back to Home
            </a>
             @if (isset($reschedule_link))
               <a href="{{ $reschedule_link }}" class="reschedule-link">
                    Reschedule
                </a>
             @endif
        </div>
    </div>
</body>

</html>

