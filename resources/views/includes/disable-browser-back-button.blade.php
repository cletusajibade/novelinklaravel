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
        }, 20000);
    }
</script>
