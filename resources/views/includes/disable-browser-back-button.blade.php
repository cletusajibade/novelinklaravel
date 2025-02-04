<script type="text/javascript">
    // Wait for the page to fully load
    window.onload = function() {

        // Prevent user from going back with the browser Back button
        // Add an entry to the history stack
        history.pushState(null, null, window.location.href);
        window.onpopstate = function() {
            // Redirect to the home page
            history.pushState(null, null, window.location.href);
            window.location.href = @json(route('home'));
        };
    }
</script>
