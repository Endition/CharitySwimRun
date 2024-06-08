<div class="container">
    <div class="row">
    <div class="col-sm-12"> 
        <h3>Kurzauskunft zu einer Startnummer</h3>
        <p>
            <ul>
                <li>Der Name wird nicht angezeigt</li>
                <li>Nummer eingeben und "Enter" drücken</li>
            </ul>
        </p>
    </div>
    <div class="row">
        <div class="col-sm-12"> 
                <div class="form-group">
                    <label>Startnummer</label>
                    <input type="number" class="form-control"name="startnummer" id="startnummer" placeholder="Startnummer" required>
                </div>
        </div>  
    </div>
    <div class="row">
        <div class="col-sm-12 mt-5" id="zieldiv" style="font-size:25px"> 
            
        </div>
    </div>

    
<script>
// Get the input field
var input = document.getElementById("startnummer");

// Execute a function when the user presses a key on the keyboard
input.addEventListener("keypress", function(event) {
  // If the user presses the "Enter" key on the keyboard
  if (event.key === "Enter" && $('#startnummer').val() !== "") { 
        $.ajax({ 
        url: "api/teilnehmer/startnummer/"+$("#startnummer").val(),
        type: 'GET', 
        error: function(jqXHR, textStatus, errorThrown ){
            var today = new Date();
            var uhrzeit = today.getHours()+":"+(today.getMinutes() < 10 ? '0' : '') + today.getMinutes() +":"+(today.getSeconds() < 10 ? '0' : '') + today.getSeconds();
            //clear input
            $("#startnummer").val('');
            //render info
            $("#zieldiv").html(uhrzeit+' Uhr: '+jqXHR.responseJSON.message+'<hr>'+$("#zieldiv").html());
        },
        success: function(data){ 
            var today = new Date();
            var uhrzeit = today.getHours()+":"+(today.getMinutes() < 10 ? '0' : '') + today.getMinutes() +":"+(today.getSeconds() < 10 ? '0' : '') + today.getSeconds();
			//clear input
            $("#startnummer").val('');
            //render info
            $("#zieldiv").html(uhrzeit+' Uhr | StNr: '+data.startnummer+' | Meter: '+data.meter+'m  | Wertung: '+data.wertung+'  | Nächste Wertung: '+data.naechsteWertung+'  <hr>'+$("#zieldiv").html());
			return false;
        }
    }); 
  }
});
</script>
