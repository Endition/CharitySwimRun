<html>
  <head>
    <script src="vendor/components/jquery/jquery.min.js"></script> 
    <style>
            .new-entry {
                background-color: green;
            }
    </style>

  </head>
  <body>
     <table id="data-table" style="width:100%;font-size:45px;background-color:black;color:white" border="1" rules="rows" >
        <thead>
            <tr>
                <th>Zeit</th>
                <th>Name</th>
                <th>StNr</th>
                <th>Bahnen</th>
                <th>Meter</th>
                <th>Rundezeit</th>
                <th>Gesamtzeit</th>
            </tr>
        </thead>
        <tbody>
            <!-- Existing rows here -->
        </tbody>
    </table>


     <script>
        $(document).ready(function(){
            var lastTimestamp = '';

            function checkForUpdates() {
                $.ajax({
                    url: 'api/teilnehmer/livebuchungen/'+lastTimestamp,
                    type: 'GET',
                    beforeSend: function(){
                        // Remove the highlighted rows when new request is send
                        $('#data-table tbody').find('tr').removeClass('new-entry');
                    },
                    success: function(entries) {
                        if (entries.length > 0) {
                           //save timestamp to compare it 
                            lastTimestamp = entries[0].timestamp;
                            for (var i = entries.length - 1; i >= 0; i--) {
                                var entry = entries[i];
                                //create row with highlight
                                var newRow = $(
                                    '<tr class="new-entry">' +
                                    '<td>' + entry.zeit + '</td>' +
                                    '<td>' + entry.gesamtname + '</td>' +
                                    '<td style="text-align: center; ">' + entry.startnummer + '</td>' +
                                    '<td style="text-align: center; ">' + entry.streckenart + '</td>' +
                                    '<td style="text-align: center; ">' + entry.meter + '</td>' +
                                    '<td style="text-align: center; ">' + entry.rundezeit + '</td>' +
                                    '<td style="text-align: center; ">' + entry.gesamtzeit + '</td>' +
                                    '</tr>'
                                );
                                //Add row to DOM
                                $('#data-table tbody').prepend(newRow);
                            }
                            // Remove excess rows if more than 20
                            $('#data-table tbody tr').slice(20).remove();
                        }
                    },
                    complete: function() {
                        setTimeout(checkForUpdates, 2000); // Check again after 2 seconds
                    },
                });
            }

            checkForUpdates(); // Initial call
        });
    </script>     
    </body>

</html>