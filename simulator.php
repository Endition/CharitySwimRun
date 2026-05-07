<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulator - Charity Swim & Run</title>
    <script src="vendor/components/jquery/jquery.min.js"></script>
    <style>
        /* Defines core variables for the simulator UI. */
        :root {
            --bg-main: #0F172A;
            --bg-card: #1E293B;
            --text-main: #F8FAFC;
            --text-muted: #94A3B8;
            --color-danger: #EF4444;
            --color-warning: #F59E0B;
            --color-success: #10B981;
            --border-subtle: rgba(255, 255, 255, 0.1);
        }

        /* Configures the base document layout and typography. */
        body {
            margin: 0;
            padding: 40px 20px;
            background-color: var(--bg-main);
            color: var(--text-main);
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        /* Styles the main container. */
        .container {
            width: 100%;
            max-width: 800px;
        }

        /* Styles the critical warning banner. */
        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            border-left: 4px solid var(--color-danger);
            padding: 20px;
            border-radius: 0 8px 8px 0;
            margin-bottom: 30px;
        }

        .alert-danger h1 {
            color: var(--color-danger);
            margin: 0 0 10px 0;
            font-size: 1.5rem;
        }

        .alert-danger p {
            margin: 0;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Styles the control panel area. */
        .controls {
            background-color: var(--bg-card);
            padding: 20px;
            border-radius: 12px;
            border: 1px solid var(--border-subtle);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .controls-info h2 {
            margin: 0 0 5px 0;
            font-size: 1.2rem;
        }

        .controls-info p {
            margin: 0;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* Styles the main toggle button with dynamic states. */
        .btn-toggle {
            font-size: 1.1rem;
            font-weight: 600;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #fff;
        }

        /* Defines the start state button style. */
        .btn-start {
            background-color: var(--color-success);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .btn-start:hover {
            background-color: #059669;
        }

        /* Defines the stop state button style. */
        .btn-stop {
            background-color: var(--color-danger);
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        .btn-stop:hover {
            background-color: #DC2626;
        }

        /* Styles the output console for simulated data. */
        .console-window {
            background-color: #000;
            border-radius: 12px;
            border: 1px solid var(--border-subtle);
            height: 500px;
            overflow-y: auto;
            padding: 20px;
            font-family: Consolas, Monaco, 'Andale Mono', 'Ubuntu Mono', monospace;
            font-size: 0.95rem;
            color: #A7F3D0;
            box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.5);
        }

        /* Styles individual log entries. */
        .log-entry {
            margin-bottom: 8px;
            border-bottom: 1px dashed rgba(255, 255, 255, 0.1);
            padding-bottom: 8px;
            animation: fadeIn 0.3s ease-out;
        }

        /* Provides a smooth fade-in for new log entries. */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateX(-10px); }
            to { opacity: 1; transform: translateX(0); }
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
<body>
    <div class="container">
        <div class="alert-danger">
            <h1>Achtung!</h1>
            <p>Nicht während der Veranstaltung live nutzen. Für den Simulator müssen Strecken und Altersklassen angelegt sein.</p>
        </div>

        <div class="controls">
            <div class="controls-info">
                <h2>Simulator Engine</h2>
                <p>Generates random bookings</p>
            </div>
            <button id="toggleBtn" class="btn-toggle btn-start">Start</button>
        </div>

        <div id="daten" class="console-window">
            <div style="color: var(--text-muted);">// System ready. Waiting to start simulation...</div>
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

                $.ajax({
                    url: 'api/teilnehmer/simulator',
                    type: 'GET',
                    success: function (entries) {
                        if (entries.length > 0) {
                            /* Clears initial placeholder text if present */
                            if ($('#daten').text().includes('System ready')) {
                                $('#daten').empty();
                            }

                            for (var i = entries.length - 1; i >= 0; i--) {
                                var logEntry = $('<div class="log-entry"></div>').text('> ' + entries[i]);
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
                    $(this).removeClass('btn-start').addClass('btn-stop').text('Stop');
                    
                    if ($('#daten').text().includes('System ready')) {
                        $('#daten').empty();
                    }
                    $('#daten').prepend('<div class="log-entry" style="color: var(--color-success);">> Simulation started...</div>');
                    
                    fetchdata();
                } else {
                    $(this).removeClass('btn-stop').addClass('btn-start').text('Start');
                    clearTimeout(timeoutId);
                    $('#daten').prepend('<div class="log-entry" style="color: var(--color-danger);">> Simulation stopped.</div>');
                }
            });
        });
    </script>
</body>
</html>
