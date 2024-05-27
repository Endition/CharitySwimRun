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
     setTimeout(fetchdata,Math.floor(Math.random() * 1000)); 
   } 
  }); 
} 
$(document).ready(function(){ 
  fetchdata(); 
});
</script>
<div id="daten" class="box"
     style="border: 2px solid black; height: 100%; background-color:black; color:white"></div>
