<html>
  <head>
    <script src="vendor/components/jquery/jquery.min.js"></script> 
    <script>
    function fetchdata(){ 
      $.ajax({ 
      url: 'api/teilnehmer/simulator', 
      type: 'GET', 
      success: function(entries) {
                            if (entries.length > 0) {
                              $('#daten').html("");
                                for (var i = entries.length - 1; i >= 0; i--) {
                                    var entry = entries[i];
                                    //create row with highlight
                                    $('#daten').prepend(entries[i]+'<br>');
                                }
                            }
                        },
      complete:function(data){ 
        setTimeout(fetchdata,Math.floor(Math.random() * 1500)); //1,5sec
      } 
      }); 
    } 
    $(document).ready(function(){ 
      fetchdata(); 
    });
    </script>
  </head>
  <body>
      <h1>Achtung!: Nicht während der Veranstaltung live nutzen.</h1>
      <div id="daten" class="box" style="border: 2px solid black; background-color:red; color:white; font-size:24">
      </div>
    </body>
</html>
