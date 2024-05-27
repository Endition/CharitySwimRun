            <div class="card">
                <h5 class="card-header">manuelle Erfassung von Impulse</h5>
                <div class="card-body">
                            <form role="form" name="ImpulseForm" id="ImpulseForm"
                                  action="{$actionurl}&action=manuell&typ=impulse" method="POST" class="form-horizontal">
                                  {include file='templateInputElement.tpl' bezeichnung='Startnummer' name='startnummer' type='number' required=true step=1}
                                  {include file='templateInputElement.tpl' bezeichnung='Anzahl Impulse' name='anzahlimpulse' type='number' required=true step=1 maxlength=3}
                                  {include file='templateInputElement.tpl' name='sendImpulseRundenData' type='submit' label='eintragen'}	
                            </form>
                </div>
            </div>
        <div class="card">
            <h5 class="card-header">Klicken, um Buchung auszulösen</h5>
            <div class="card-body">
            {include file='templateInputElement.tpl'  bezeichnung='Filtern nach Strecke'  name='strecken[]' id='strecken' type='select' multiple=true selectValueList=$strecken emptyElement=true  }
                        </div>	
        </div>

        <div class="card">
            <div class="card-body" >	
                <div class="container" >
                    <div class="row" id="manuelleEingabeButtons">
                    </div>	
                </div>	
            </div>
        </div>

<script  type="text/javascript">
    {literal}
        jQuery(document).ready(function () {
            var derNameLautet =  {/literal} {$anzuzeigendeStrecken}{literal};
            doAjaxManuelleEingabeSelect(derNameLautet);
        });

        $("#strecken").change(function () {
            $("#manuelleEingabeButtons").html("");
            doAjaxManuelleEingabeSelect($("#strecken").val());
        });

        function doAjaxManuelleEingabeSelect(id_var) {
            var typ_var = "strecke";
            jQuery.ajax({
                url: "api/teilnehmer/strecke/"+id_var,
                method: "GET",
                dataType: "json",
                error: function (result) {
                    jQuery("#manuelleEingabeButtons").html("Fehler in der Abfrage");
                },
                success: function (result) {
                    result.forEach(myFunction);
                        
                }});
        }
        

function myFunction(item) {
    let inputField = jQuery("#manuelleEingabeButtons");
    inputField.html(inputField.html()+"<div class=\"d-grid gap-2 col-2 mx-auto\"><button type=\"button\" name=\"sendImpulseEinlaufenData\" onclick=\"doAjaxManuelleEingabeInsert("+item.id+")\" value=\""+item.id+"\" class=\"btn btn-primary\">"+item.startnummer+":<br> "+item.vorname+" "+item.name+"</button></div>");
}
        function doAjaxManuelleEingabeInsert(id_var) {
            var typ_var = "strecke";
            jQuery.ajax({
                url: "api/impulse/create",
                data: {typ: typ_var, id: id_var},
                method: "POST",
                dataType: "json",
                error: function (result) {
                    toastManager.show('Fehler beim Speichern Impuls', 'Fehler', 'error', 3000);
                    jQuery("#messages").html("Fehler");
                },
                success: function (result) {
                    toastManager.show('Impuls gespeichert', 'Erfolgreich', 'success', 3000);
                }});
        }
    {/literal}
</script>