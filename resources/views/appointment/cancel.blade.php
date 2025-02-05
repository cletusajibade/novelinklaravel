<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cancel Appointment</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

        .cancel-container {
            max-width: 500px;
            background: #fff;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .cancel-header {
            font-size: 24px;
            color: #dc3545;
            margin-bottom: 20px;
        }

        .cancel-details {
            font-size: 16px;
            color: #333;
            margin-bottom: 20px;
            text-align: left;
        }

        .cancel-details p {
            margin: 5px 0;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .button {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            font-size: 14px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .cancel-button {
            background-color: #dc3545;
        }

        .cancel-button:hover {
            background-color: #a71d2a;
        }

        .home-button {
            background-color: #007bff;
        }

        .home-button:hover {
            background-color: #0056b3;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>

<body>
    <div class="cancel-container">
         @if (session('success'))
            <x-bladewind::alert type="success" shade="dark">
                {{ session('success') }}
            </x-bladewind::alert>
        @endif
        @if (session('warning'))
            <x-bladewind::alert type="warning" shade="dark">
                {{ session('warning') }}
            </x-bladewind::alert>
        @endif
        @if (session('error'))
            <x-bladewind::alert type="error" shade="dark">
                {{ session('error') }}
            </x-bladewind::alert>
        @endif
        @if ($errors->any())
            <div
                style="background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 0.75rem 1rem; border-radius: 0.25rem; margin-bottom: 1rem;">
                <ul style="list-style-type: none; padding: 0; margin: 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="cancel-header">Cancel Appointment</div>
        <div class="cancel-details">
            <p><strong>Name:</strong> {{$first_name." ".$last_name}}</p>
            <p><strong>Appointment Date:</strong> {{$date}}</p>
            <p><strong>Time:</strong> {{$time}}</p>
        </div>
        <div class="button-container">
            <a href="{{route('home')}}" class="button home-button">Back to Home</a>
            <button class="button cancel-button">Cancel Appointment</button>
        </div>
    </div>
    <script>
        const cancelButton = document.querySelector('.cancel-button');

        // Set the CSRF token for all Axios requests
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute(
            'content');

        // Get the client's timezone
        const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        // Get the client's locale
        const locale = navigator.language || navigator.userLanguage;

        // Get the current route
        const route = window.location.href;

        cancelButton.addEventListener("click", (e) => {
            e.preventDefault();

            // Send client_id, date, time slot, and duration back to the server to process
            const data = {
                clientId: @json($client_id),
                timezone: timezone,
                locale: locale
            };

            // Send POST request
            axios.post(route, data)
                .then(response => {
                    // console.log('response.data.message: ', response.data.message);

                    // Reload the page for the flashed success message to display
                    location.reload(true);
                })
                .catch(error => {
                    if (error.response) {
                        // Server responded with a status code
                        console.error('Error:', error.response.data.error);
                    } else if (error.request) {
                        // Request was made but no response received
                        console.error('No response received:', error.request);
                    } else {
                        // Something else caused the error
                        console.error('Error:', error.message);
                    }

                    // Reload the page for the flashed error message to display
                    location.reload(true);
                });
        });
    </script>
</body>

</html>
