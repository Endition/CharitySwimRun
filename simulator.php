<html>
  <head>
    <script src="vendor/components/jquery/jquery.min.js"></script> 
    <script>
    var isRunning = false;
    var timeoutId = null;

    /**
     * Fetches simulator data from the API and updates the view.
     * Schedules the next execution randomly if the simulator is running.
     */
    function fetchdata(){ 
      if (!isRunning) return;
      
      $.ajax({ 
        url: 'api/teilnehmer/simulator', 
        type: 'GET', 
        success: function(entries) {
          if (entries.length > 0) {
            $('#daten').html("");
            for (var i = entries.length - 1; i >= 0; i--) {
              $('#daten').prepend(entries[i]+'<br>');
            }
          }
        },
        complete: function(data){ 
          if (isRunning) {
            timeoutId = setTimeout(fetchdata, Math.floor(Math.random() * 1500)); // 1.5s max
          }
        } 
      }); 
    } 

    $(document).ready(function(){ 
      $('#toggleBtn').click(function() {
        isRunning = !isRunning;
        if (isRunning) {
          $(this).text('Stop');
          fetchdata();
        } else {
          $(this).text('Start');
          clearTimeout(timeoutId);
        }
      });
    });
    </script>
  </head>
  <body>
      <h1>Achtung!: Nicht während der Veranstaltung live nutzen.</h1>
      <p>Für den Simulator müssen Strecken und Altersklassen angelegt sein.</p>
      
      <button id="toggleBtn" style="font-size: 24px; margin-bottom: 20px; padding: 10px 20px; cursor: pointer;">Start</button>
      
      <div id="daten" class="box" style="border: 2px solid black; background-color:red; color:white; font-size:24">
      </div>
    </body>
</html>
