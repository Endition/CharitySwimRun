<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#172554">
    <title>Simulator - Charity Swim & Run</title>
    <script src="vendor/components/jquery/jquery.min.js"></script>
    <link href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Styles the output console for simulated data. */
        .console-window {
            background-color: #000;
            height: 500px;
            overflow-y: auto;
            font-family: Consolas, Monaco, 'Andale Mono', 'Ubuntu Mono', monospace;
            font-size: 0.95rem;
            color: #A7F3D0;
            box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.5);
        }

        /* Styles individual log entries. */
        .log-entry {
            border-bottom: 1px dashed rgba(255, 255, 255, 0.1);
            animation: fadeIn 0.3s ease-out;
        }

        /* Provides a smooth fade-in for new log entries. */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Customizes the scrollbar for the console window. */
        .console-window::-webkit-scrollbar {
            width: 8px;
        }

        .console-window::-webkit-scrollbar-track {
            background: #000;
        }

        .console-window::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }
    </style>
</head>

<body class="bg-dark text-light d-flex flex-column align-items-center min-vh-100 py-5 px-3">
    <div class="container" style="max-width: 800px;">
        <div class="alert alert-danger shadow-sm border-0 mb-4 px-4 py-3">
            <h1 class="h4 text-danger mb-2 fw-bold">Achtung!</h1>
            <p class="mb-0 text-dark">Nicht während der Veranstaltung live nutzen. Für den Simulator müssen Strecken und
                Altersklassen angelegt sein.</p>
        </div>

        <div class="card bg-secondary text-light mb-4 shadow border-0 rounded-3">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div class="flex-grow-1">
                    <h2 class="h5 mb-1 fw-bold">Simulator Engine</h2>
                    <p class="mb-0 text-light opacity-75 small">Generates random bookings</p>
                </div>
                <div class="me-3">
                    <label for="modeSelect" class="small text-light opacity-75 d-block mb-1">Mode:</label>
                    <select id="modeSelect" class="form-select form-select-sm bg-dark text-light border-secondary">
                        <option value="log">Direct Log (Hit)</option>
                        <option value="cache">Cache (RFID Trigger)</option>
                    </select>
                </div>
                <button id="toggleBtn" class="btn btn-success fw-bold text-uppercase px-4 py-2 shadow-sm">Start</button>
            </div>
        </div>

        <div id="daten" class="console-window border border-secondary rounded-3 p-4">
            <div class="text-secondary">// System ready. Waiting to start simulation...</div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            var isRunning = false;
            var timeoutId = null;

            /**
             * Fetches simulator data from the API and updates the view.
             * Schedules the next execution randomly if the simulator is running.
             */
            function fetchdata() {
                if (!isRunning) return;

                var mode = $('#modeSelect').val();
                $.ajax({
                    url: 'api/teilnehmer/simulator/' + mode,
                    type: 'GET',
                    success: function (entries) {
                        if (entries.length > 0) {
                            /* Clears initial placeholder text if present */
                            if ($('#daten').text().includes('System ready')) {
                                $('#daten').empty();
                            }

                            for (var i = entries.length - 1; i >= 0; i--) {
                                var logEntry = $('<div class="log-entry mb-2 pb-2"></div>').text('> ' + entries[i]);
                                $('#daten').prepend(logEntry);
                            }

                            /* Limits the console history to prevent excessive memory usage. */
                            var maxLogs = 100;
                            if ($('#daten .log-entry').length > maxLogs) {
                                $('#daten .log-entry').slice(maxLogs).remove();
                            }
                        }
                    },
                    complete: function () {
                        if (isRunning) {
                            /* Schedules the next cycle with random delay up to 1.5 seconds. */
                            timeoutId = setTimeout(fetchdata, Math.floor(Math.random() * 1500));
                        }
                    }
                });
            }

            /**
             * Handles the start/stop toggle logic for the simulation engine.
             */
            $('#toggleBtn').click(function () {
                isRunning = !isRunning;
                if (isRunning) {
                    $(this).removeClass('btn-success').addClass('btn-danger').text('Stop');

                    if ($('#daten').text().includes('System ready')) {
                        $('#daten').empty();
                    }
                    $('#daten').prepend('<div class="log-entry mb-2 pb-2 text-success">> Simulation started...</div>');

                    fetchdata();
                } else {
                    $(this).removeClass('btn-danger').addClass('btn-success').text('Start');
                    clearTimeout(timeoutId);
                    $('#daten').prepend('<div class="log-entry mb-2 pb-2 text-danger">> Simulation stopped.</div>');
                }
            });

            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('sw.js');
                });
            }
        });
    </script>
</body>

</html>