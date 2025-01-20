<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Payment Status</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- BladewindUI-->
    <link href="{{ asset('vendor/bladewind/css/animate.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/bladewind/css/bladewind-ui.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('vendor/bladewind/js/helpers.js') }}"></script>

</head>

<body>
    <x-bladewind::alert type="success" shade="dark" class="my-alert">
        Registration successful. Kindly check your email for next steps.
        <br /> Closing this tab...
    </x-bladewind::alert>

    <script></script>

    <script type="text/javascript">
        // Wait for the page to fully load
        window.onload = function() {

            // Prevent user from going back with the browser Back button
            // Add an entry to the history stack
            history.pushState(null, null, window.location.href);
            window.onpopstate = function() {
                // Redirect or show a custom message
                history.pushState(null, null, window.location.href);
                alert(
                "Registration successful. Kindly check your email for next steps. click OK to close the tab.");
            };

            setTimeout(() => {
                // Check if the window was opened via JavaScript
                if (window.opener) {
                    // Close the current window (tab)
                    window.close();
                } else {
                    // Optionally, notify the user that they can manually close the tab
                    alert("Registration successful. Kindly check your email for next steps. You can now close the tab.");
                }
            }, 7000);
        }
    </script>
</body>

</html>
