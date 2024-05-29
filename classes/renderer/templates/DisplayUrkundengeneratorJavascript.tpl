<script type="text/javascript">
//Macht die Felder draggable    
jQuery(function() {
    //sorgt dafuer das Elemente sich in der Groesse aendern lassen
    jQuery(".ui-widget-content").resizable({
        helper: "ui-resizable-helper"
        //einbau von containment f�hrt zu fehler
    });

    //sorgt dafuer das Elemente verschiebbar sind
    jQuery(".ui-widget-content").draggable({
        containment: "#seite",
        stop: function(event, ui) {
            var PosStop = $(this).position();
            jQuery('div#stop').text("STOP: \nLeft: " + PosStop.left + "\nTop: " + PosStop.top);
        }
    });

});

//Funktion das alle IDs durchläuft und die aktuelle Position holt und in Arry schreibe -> Dann update
function getPostion() {
    var xy = new Array();
    $felder = $(".div.ui-widget-content");
    //Alle Felder durchlaufen und die Postionen abfragen
    jQuery(".ui-widget-content").each(function() {
        var PosStop = jQuery(this).position();
        var Width = jQuery(this).width();
        var Height = jQuery(this).height();
        var ID = jQuery(this).attr("id");

        //Datenstring bauen und per Ajaxrequest an die DB senden
        var dataString = 'id=' + ID + '&x_wert=' + PosStop.left + '&y_wert=' + PosStop.top + '&breite=' + Width + '&hoehe=' + Height;
        var jqxhr = jQuery.ajax({
			type: "POST",
			url: "api/urkunden/update",
			data: dataString,
			cache: false,
        });
        //Das wird ausgef�hrt wenn Buchung erfolgreich war
        jqxhr.done(function(msg) {
            $('div#stop').append("->ID: " + ID + ": X: " + PosStop.left + " Y: " + PosStop.top + " -> gespeichert<br />");
        });
        //Wenn eine Fehler beim Request aufgetreten ist
        jqxhr.fail(function(jqXHR, textStatus) {
            alert("Aktion Fehlgeschlagen!: " + textStatus);
        });
    });
}
  </script>
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<h5 class="card-header">Urkundengenrator</h5>
			<div class="card-body">
				<div class="row">
					<div class="col-lg-12">
						<h3>Urkunden erstellen</h3>
						<p>Hinweise</p>
						 <ul>
						 	<li><b>X-Wert</b>: Gibt die Positionierung des Elements auf der X-Achse (horizontal) an. Positiver Werte -> Ausgehend von der oberen rechten Ecke. Negative Werte -> Ausgehend von der unteren linken Ecke</li>
							<li><b>Y-Wert</b>: Gibt die Positionierung des Elements auf der Y-Achse (vertkal) an. Positiver Werte -> Ausgehend von der oberen rechten Ecke. Negative Werte -> Ausgehend von der unteren linken Ecke</li>
							<li><b>Tipp</b>: Der X-Wert geht von 0 - 595, Der Y-Wert von 0 - 842</li>
						</ul>
						<p>Anleitung</p>
						<ol>
							<li>Elemente über das obere Formular hinzufügen und eine grobe Positionierung vornehmen</li>
							<li>Elemente im Vorschaubereich positionieren und auf die richtige Größe ziehen und speichern</li>
							<li>Testurkunde drucken, ggf. nachjustieren. (Die Vorschau ist nur ein anhalt)</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<style>.ui-resizable-helper { border: 2px dotted #00F; } </style> 

<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<h5 class="card-header">Vorschau auf die Urkunde und Positionierung der Elemente</h5>
			<div class="card-body">
				<div class="row">
					<div class="col-lg-12">
					<div id="stop">Warte das Elemente bewegt werden...<br /></div>
					<button type="submit" class="btn btn-primary" onclick="getPostion()">Position der Elemente speichern</button> 
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
					
					<div id="seite" style="width:420px; height:594px; position:absolute;border: 2px solid black;  background-color:white"> 
							{foreach from=$urkundenelemente key=schluessel item=value}
								<div id="{$value->getId()}" class="ui-widget-content" style="width:{$value->getBreite()}px; height: {$value->getHoehe()}px; padding: 0px;position:absolute; margin:0px; top:{$value->getY_wert()}px; left:{$value->getX_wert()}px; ">
			       					<p style="font-size:{$value->getschriftgroesse()} px; {$HTMLSchrifttyp[{$value->getschrifttyp()}]}; text-align: {$HTMLTextAusrichtung[{$value->getAusrichtung()}]} "> {$value->getInhaltFreitext()}</p>
			    				 </div>
							{/foreach}
					</div>
					