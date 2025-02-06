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
       <div id="successMessage" style="display: none;"></div>
        <div id="warningMessage" style="display: none;"></div>
        <div id="errorMessage" style="display: none;"></div>

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
            {{-- <p><strong>Date: </strong><span id="date"></span></p> --}}<!-- TODO: date not formating correctly -->
            <p><strong>Date:</strong> {{$date}}</span></p>
            <p><strong>Time: </strong><span id="time"></span></p>
        </div>
        <div class="button-container">
            <a href="{{route('home')}}" class="button home-button">Back to Home</a>
            <button class="button cancel-button">Cancel Appointment</button>
        </div>
    </div>
    <script>
        const cancelButton = document.querySelector('.cancel-button');
        const clientTime = document.getElementById('time');
        const successMessageDiv = document.getElementById('successMessage');
        const warningMessageDiv = document.getElementById('warningMessage');
        const errorMessageDiv = document.getElementById('errorMessage');

        // Set the CSRF token for all Axios requests
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute(
            'content');

        // Get the client's timezone
        const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

        // Get the client's locale
        const locale = navigator.language || navigator.userLanguage;

        // Get the current route
        const route = window.location.href;

        const convertedTime = mstToClientTz(@json($time), timezone, locale);
        clientTime.innerHTML = convertedTime;

        // const formatedDate = formatDateToClientLocale(@json($date), timezone, locale);
        // clientDate.innerHTML = formatedDate;

        cancelButton.addEventListener("click", (e) => {
            e.preventDefault();

            const data = {
                timezone: timezone,
                locale: locale
            };

            // Send POST request
            axios.post(route, data)
                .then(response => {
                    // console.log('response.data.message: ', response.data.message);
                    successMessageDiv.innerHTML =
                        `<x-bladewind::alert type="success" shade="dark">
                            ${response.data.message}
                        </x-bladewind::alert>`;
                    successMessageDiv.style.display = 'block';

                    if (response.data.redirect) {
                        window.location.href = response.data.redirect_url;
                    }
                })
                .catch(error => {
                   // console.log('error:', error);
                    if (error.response) {
                        // Server responded with a status code
                        console.error('Error:', error.response.data.error);

                        if (error.response.data.redirect) {
                            if (error.response.data.delay_redirect) {
                                errorMessageDiv.innerHTML =
                                `<x-bladewind::alert type="error" shade="dark">
                                        ${error.response.data.error}
                                    </x-bladewind::alert>`;
                                errorMessageDiv.style.display = 'block';

                                window.setTimeout(function() {
                                    window.location.href = error.response.data.redirect_url;
                                }, 5000);
                            } else {
                                window.location.href = error.response.data.redirect_url;
                            }
                        } else {
                            errorMessageDiv.innerHTML =
                                `<x-bladewind::alert type="error" shade="dark">
                                    ${error.response.data.error}
                                </x-bladewind::alert>`;
                            errorMessageDiv.style.display = 'block';
                        }

                    } else if (error.request) {
                        // Request was made but no response received
                        console.error('No response received:', error.request);
                        errorMessageDiv.innerHTML =
                            `<x-bladewind::alert type="error" shade="dark">Error processing your appointment request. Contact us for further help.</x-bladewind::alert>`;
                        errorMessageDiv.style.display = 'block';
                    } else {
                        // Something else caused the error
                        console.error('Error:', error.message);
                        errorMessageDiv.innerHTML =
                            `<x-bladewind::alert type="error" shade="dark">${error.message}</x-bladewind::alert>`;
                        errorMessageDiv.style.display = 'block';
                    }
                });
        });

         function mstToClientTz(mstTime, clientTimezone, locale) {
            // Original time in MST (Mountain Standard Time) - no seconds part (H:i format)
            mstTime = removeSeconds(mstTime);

            // Get the current date of the client
            const currentDate = new Date();
            const currentDateString = currentDate.toISOString().split('T')[0]; // Format: YYYY-MM-DD

            // Combine the current date with the MST time string
            const mstDateTimeString = `${currentDateString}T${mstTime}:00-07:00`; // Assuming MST (UTC -7)

            // Convert the MST time to a JavaScript Date object
            const mstDate = new Date(mstDateTimeString);

            // Convert MST time to the client's time zone using toLocaleString
            const options = {
                hour: '2-digit',
                minute: '2-digit',
                timeZone: clientTimezone
            };
            const convertedTime = mstDate.toLocaleString(locale, options);

            // Get the client's timezone abbreviation (automatically adjusts for DST (daylight saving))
            const clientTimeAbbr = new Date().toLocaleString(locale, {
                timeZone: clientTimezone,
                timeZoneName: 'short'
            }).split(' ').pop();

            // Return the converted time along with the timezone abbreviation
            return convertedTime + " " + clientTimeAbbr;
        }

         function removeSeconds(timeWithSeconds) {
            // Split the time string into hours, minutes, and seconds
            const [hours, minutes] = timeWithSeconds.split(':');
            return `${hours}:${minutes}`;
        }

        function formatDateToClientLocale(dateString, clientTimezone, locale) {
            // Convert the input string to a valid JavaScript Date object
            const date = new Date(dateString);

            // Format the date using the client's locale and timezone
            const options = {
                year: 'numeric',
                month: 'long', // Full month name
                day: 'numeric',
                timeZone: clientTimezone // Ensure it respects the client's timezone
            };

            return date.toLocaleDateString(locale, options);
        }

    </script>
</body>

</html>
