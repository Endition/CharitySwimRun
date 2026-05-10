<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#172554">
    <title>Livebuchungen - Charity Swim & Run</title>
    <script src="vendor/components/jquery/jquery.min.js"></script>
    <link href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --accent-glow: rgba(56, 189, 248, 0.4);
            --epic-glow: rgba(250, 204, 21, 0.5);
        }

        body {
            background: linear-gradient(135deg, #0B1120 0%, #172554 100%);
            overflow: hidden;
            color: #F8FAFC;
        }

        .header h1 {
            background: -webkit-linear-gradient(45deg, #38BDF8, #818CF8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 20px rgba(56, 189, 248, 0.3);
        }

        .table-container {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(10px);
        }

        .table > :not(caption) > * > * {
            background-color: transparent;
            color: #F8FAFC;
            border-bottom-color: rgba(255, 255, 255, 0.05);
        }

        .table thead th {
            background: rgba(15, 23, 42, 0.8);
            color: #94A3B8;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        }

        tr { transition: all 0.3s ease; }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes highlightNormal {
            0% { background-color: rgba(56, 189, 248, 0.5); box-shadow: inset 0 0 20px var(--accent-glow); }
            100% { background-color: transparent; box-shadow: none; }
        }

        @keyframes highlightEpic {
            0% { background-color: rgba(250, 204, 21, 0.6); box-shadow: inset 0 0 30px var(--epic-glow); transform: scale(1.01); }
            50% { background-color: rgba(250, 204, 21, 0.3); }
            100% { background-color: transparent; box-shadow: none; transform: scale(1); }
        }

        .new-entry { animation: slideIn 0.5s ease-out forwards, highlightNormal 3s ease-out forwards; }
        
        .new-entry-round-number {
            animation: slideIn 0.5s ease-out forwards, highlightEpic 4s ease-out forwards;
            font-weight: 700;
            color: #FDE047 !important;
            text-shadow: 0 0 10px rgba(250, 204, 21, 0.5);
        }
        .new-entry-round-number td {
             color: #FDE047 !important;
        }

        ::-webkit-scrollbar { display: none; }
    </style>
</head>

<body class="min-vh-100 p-4">
    <div class="text-center mb-4 text-uppercase" style="letter-spacing: 4px;">
    </div>

    <div class="table-container container-fluid border border-secondary border-opacity-25 rounded-4 shadow-lg overflow-hidden p-0" style="max-width: 1800px;">
        <table id="data-table" class="table table-borderless fs-2 align-middle mb-0">
            <thead class="text-uppercase" style="letter-spacing: 2px;">
                <tr>
                    <th class="py-3 px-4">Zeit</th>
                    <th class="py-3 px-4">Name</th>
                    <th class="text-center py-3 px-4">StNr</th>
                    <th class="text-center py-3 px-4">Bahnen</th>
                    <th class="text-center py-3 px-4">Meter</th>
                    <th class="text-center py-3 px-4">Rundezeit</th>
                    <th class="text-end py-3 px-4" style="font-variant-numeric: tabular-nums;">Gesamtzeit</th>
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

            function checkForUpdates() {
                $.ajax({
                    url: 'api/teilnehmer/livebuchungen/' + lastTimestamp,
                    type: 'GET',
                    cache: false,
                    success: function (entries) {
                        if (entries.length > 0) {
                            lastTimestamp = entries[0].timestamp;

                            for (var i = entries.length - 1; i >= 0; i--) {
                                var entry = entries[i];
                                var isEpic = (entry.meter > 0 && entry.meter % 2500 === 0);
                                var rowClass = isEpic ? 'new-entry-round-number' : 'new-entry';
                                var meterDisplay = isEpic ? '⭐ ' + entry.meter + 'm' : entry.meter + 'm';

                                var newRow = $(
                                    '<tr class="' + rowClass + '">' +
                                    '<td class="py-4 px-4"><span class="badge bg-light bg-opacity-10 rounded-pill px-3 py-2 fs-3">' + entry.zeit + '</span></td>' +
                                    '<td class="py-4 px-4 fw-bold text-white">' + entry.gesamtname + '</td>' +
                                    '<td class="text-center py-4 px-4">' + entry.startnummer + '</td>' +
                                    '<td class="text-center py-4 px-4">' + entry.streckenart + '</td>' +
                                    '<td class="text-center py-4 px-4">' + meterDisplay + '</td>' +
                                    '<td class="text-center py-4 px-4">' + entry.rundezeit + '</td>' +
                                    '<td class="text-end py-4 px-4" style="font-variant-numeric: tabular-nums;">' + entry.gesamtzeit + '</td>' +
                                    '</tr>'
                                );

                                $('#data-table tbody').prepend(newRow);
                            }

                            var maxRows = 12;
                            $('#data-table tbody tr').not('.is-leaving').each(function (index) {
                                if (index >= maxRows) {
                                    $(this).addClass('is-leaving').fadeOut(400, function () { $(this).remove(); });
                                }
                            });
                        }
                    },
                    complete: function () {
                        setTimeout(checkForUpdates, 1000);
                    },
                    error: function () {
                        setTimeout(checkForUpdates, 5000);
                    }
                });
            }

            checkForUpdates();
        });
    </script>
</body>

</html>