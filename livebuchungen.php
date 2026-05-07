<html>

<head>
    <script src="vendor/components/jquery/jquery.min.js"></script>
    <style>
        /* Defines the core color palette and glow variables for the live ticker. */
        :root {
            --bg-color: #0B1120;
            --card-bg: rgba(30, 41, 59, 0.7);
            --text-main: #F8FAFC;
            --text-muted: #94A3B8;
            --accent-glow: rgba(56, 189, 248, 0.4);
            --epic-glow: rgba(250, 204, 21, 0.5);
        }

        /* Configures the main background gradient, system font stack, and prevents scrolling. */
        body {
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #0B1120 0%, #172554 100%);
            color: var(--text-main);
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            min-height: 100vh;
            overflow: hidden;
        }

        /* Styles the main header section. */
        .header {
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 4px;
        }

        /* Applies a linear gradient text effect and soft shadow to the main heading. */
        .header h1 {
            font-weight: 900;
            font-size: 3rem;
            margin: 0;
            background: -webkit-linear-gradient(45deg, #38BDF8, #818CF8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 20px rgba(56, 189, 248, 0.3);
        }

        /* Creates a glassmorphism card effect to contain the data table. */
        .table-container {
            width: 100%;
            max-width: 1800px;
            margin: 0 auto;
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            overflow: hidden;
        }

        /* Sets up the basic structure and sizing for the data table. */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 2.2rem;
        }

        /* Styles table headers with a semi-transparent background and uppercase text. */
        th {
            background: rgba(15, 23, 42, 0.8);
            color: var(--text-muted);
            padding: 20px 30px;
            text-align: left;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        }

        /* Defines padding and subtle borders for table cells. */
        td {
            padding: 25px 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            font-weight: 400;
        }

        /* Ensures smooth transitions for any row state changes. */
        tr {
            transition: all 0.3s ease;
        }

        /* Animates new rows sliding down from the top while fading in. */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Creates a temporary blue glow effect for standard incoming entries. */
        @keyframes highlightNormal {
            0% {
                background-color: rgba(56, 189, 248, 0.5);
                box-shadow: inset 0 0 20px var(--accent-glow);
            }

            100% {
                background-color: transparent;
                box-shadow: none;
            }
        }

        /* Creates a pronounced golden pulse effect for milestone entries. */
        @keyframes highlightEpic {
            0% {
                background-color: rgba(250, 204, 21, 0.6);
                box-shadow: inset 0 0 30px var(--epic-glow);
                transform: scale(1.01);
            }

            50% {
                background-color: rgba(250, 204, 21, 0.3);
            }

            100% {
                background-color: transparent;
                box-shadow: none;
                transform: scale(1);
            }
        }

        /* Applies the slide-in and normal glow animations to new rows. */
        .new-entry {
            animation: slideIn 0.5s ease-out forwards, highlightNormal 3s ease-out forwards;
        }

        /* Applies the slide-in and epic glow animations to milestone rows. */
        .new-entry-round-number {
            animation: slideIn 0.5s ease-out forwards, highlightEpic 4s ease-out forwards;
            font-weight: 700;
            color: #FDE047;
            text-shadow: 0 0 10px rgba(250, 204, 21, 0.5);
        }

        .col-center {
            text-align: center;
        }

        .col-right {
            text-align: right;
            font-variant-numeric: tabular-nums;
        }

        .name-highlight {
            font-weight: 600;
            color: #FFFFFF;
        }

        /* Styles the time badge. */
        .badge {
            background: rgba(255, 255, 255, 0.1);
            padding: 5px 15px;
            border-radius: 12px;
            font-size: 1.8rem;
        }

        /* Hides the scrollbar for a cleaner display. */
        ::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body>
    <div class="header">
    </div>

    <div class="table-container">
        <table id="data-table">
            <thead>
                <tr>
                    <th>Zeit</th>
                    <th>Name</th>
                    <th class="col-center">StNr</th>
                    <th class="col-center">Bahnen</th>
                    <th class="col-center">Meter</th>
                    <th class="col-center">Rundezeit</th>
                    <th class="col-right">Gesamtzeit</th>
                </tr>
            </thead>
            <tbody>
                <!-- Rows will be injected here -->
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function () {
            var lastTimestamp = '';

            /**
             * Fetches the latest booking entries from the API based on the last known timestamp.
             * Triggers animations for new entries and removes older entries to prevent overflow.
             */
            function checkForUpdates() {
                $.ajax({
                    url: 'api/teilnehmer/livebuchungen/' + lastTimestamp,
                    type: 'GET',
                    success: function (entries) {
                        if (entries.length > 0) {
                            lastTimestamp = entries[0].timestamp;

                            for (var i = entries.length - 1; i >= 0; i--) {
                                var entry = entries[i];

                                /* Determines if the current entry represents a distance milestone. */
                                var isEpic = (entry.meter > 0 && entry.meter % 2500 === 0);
                                var rowClass = isEpic ? 'new-entry-round-number' : 'new-entry';
                                var meterDisplay = isEpic ? '⭐ ' + entry.meter + 'm' : entry.meter + 'm';

                                var newRow = $(
                                    '<tr class="' + rowClass + '">' +
                                    '<td><span class="badge">' + entry.zeit + '</span></td>' +
                                    '<td class="name-highlight">' + entry.gesamtname + '</td>' +
                                    '<td class="col-center">' + entry.startnummer + '</td>' +
                                    '<td class="col-center">' + entry.streckenart + '</td>' +
                                    '<td class="col-center">' + meterDisplay + '</td>' +
                                    '<td class="col-center">' + entry.rundezeit + '</td>' +
                                    '<td class="col-right">' + entry.gesamtzeit + '</td>' +
                                    '</tr>'
                                );

                                $('#data-table tbody').prepend(newRow);
                            }

                            /* Limits the visible rows to maintain performance and layout. */
                            var maxRows = 12;
                            $('#data-table tbody tr').each(function (index) {
                                if (index >= maxRows) {
                                    $(this).fadeOut(400, function () { $(this).remove(); });
                                }
                            });
                        }
                    },
                    complete: function () {
                        /* Schedules the next update check. */
                        setTimeout(checkForUpdates, 1000);
                    },
                    error: function () {
                        /* Increases the delay before the next check if an error occurs. */
                        setTimeout(checkForUpdates, 5000);
                    }
                });
            }

            checkForUpdates();
        });
    </script>
</body>

</html>