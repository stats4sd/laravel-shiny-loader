<div>
    <div id="shiny-loading-message"></div>
    <iframe src="{{ $shinyAppUrl }}" width="100%" height="1000px"></iframe>

    <script>
        function showAuthError() {
            document.getElementById('shiny-loading-message').innerHTML =
                "An error occurred during authentication. Please reload the page to try again. If the problem persists, please contact your system administrator.";
        }

        function showAuthSuccess() {
            document.getElementById('shiny-loading-message').className = "hidden";
        }


        window.addEventListener("message", function(event) {

            // only accept messages from the shiny app
            const shinyOrigin = getBaseUrl("{{ $shinyAppUrl }}")

            if (event.origin !== shinyOrigin) {
                return;
            }

            fetch("{{ route('laravel-shiny.shiny-auth') }}", {
                    method: "POST",
                    body: JSON.stringify({
                        'session': event.data,
                        'post_data': @json($postData)
                    }),
                    headers: {
                        'Content-Type': 'application/json',
                        "X-CSRF-Token": "{{ csrf_token() }}",
                    },
                })
                .then((res) => {
                    showAuthSuccess();
                })
                .catch((err) => {
                    console.log(err);
                    if (err.response && err.response.status === 403) {
                        alert(err.message + ": " + err.response ?? err.response.data.message)
                        showAuthError();
                    }

                    @if (config('app.env') === 'live')
                        document.getElementById('shiny-loading-message').innerHTML =
                            "An error occurred during authentication. Please reload the page to try again. If the problem persists, please contact your system administrator.";
                    @else
                        document.getElementById('shiny-loading-message').innerHTML =
                            "An error occurred during authentication. It looks like the Shiny app did not respond with a 200 status to the POST request. Please check that the Shiny app is correctly configured.<br/><br/>(This message will not appear on the live site - it only appears here to help debugging)";
                    @endif
                })

        }, false)


        console.log('shiny-loader loaded');


        function getBaseUrl(url) {
            const parsedUrl = new URL(url);
            return `${parsedUrl.protocol}//${parsedUrl.host}`;
        }
    </script>


</div>
