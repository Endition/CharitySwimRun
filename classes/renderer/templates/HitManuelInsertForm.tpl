            <div class="card">
                <h5 class="card-header">manuelle Erfassung von Impulse</h5>
                <div class="card-body">
                            <form role="form" name="ImpulseForm" id="ImpulseForm"
                                  action="{$actionurl}&action=manuell&typ=impulse" method="POST" class="form-horizontal">
                                  {include file='templateInputElement.tpl' bezeichnung='Startnummer' name='startnummer' type='number' required=true step=1}
                                  {include file='templateInputElement.tpl' bezeichnung='Anzahl Impulse' name='anzahlimpulse' type='number' required=true step=1 maxlength=3 description="Anschläge am Leser/an der Person. Umrechnung in Bahnen o.ä erfolgt automatisch"}
                                  {include file='templateInputElement.tpl' name='sendImpulseRundenData' type='submit' label='eintragen'}	
                            </form>
                </div>
            </div>
        <div class="card">
            <h5 class="card-header">Indiviudelles Set zusammen stellen, und Starter per Klicken buchen</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        {include file='templateInputElement.tpl'  bezeichnung='Strecke hinzufügen'  name='addDistance' id='addDistance' type='select' multiple=false selectValueList=$strecken emptyElement=true  }
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label">Startnummer hinzufügen. <small>Die IdentNr ist optional</small></label>
                            <div class="col-sm-8">
                                <div class="input-group mb-3">
                                    <input type="number" step="1" min=1 class="form-control" placeholder="IdentNr" name="addIdentnumber" id="addIdentnumber" aria-describedby="basic-addon2">
                                    <input type="number" step="1" min=1 class="form-control" placeholder="Startnummer"  name="addStartnumber" id="addStartnumber" aria-describedby="basic-addon2" required>
                                    <div class="input-group-append">
                                    <button class="btn btn-primary" id="addStartnumberButton" type="button">hinzufügen</button>
                                    </div>
                                 </div>
                             </div>
                        </div>
                    </div>     
                </div>  
            </div>	
        </div>

        <div class="card">
            <h5 class="card-header">Klicken, um Buchung auszulösen</h5>
            <div class="card-body" >	
                    <div class="row sortable-numeric" id="manuelleEingabeButtons">
                    </div>	
            </div>
        </div>

<script  type="text/javascript">
    {literal}
     $(document).ready(function() {

        //remove button from DOM by clicking the red X
        //event delegation, because buttons added dynamicly
        $("#manuelleEingabeButtons").on( "click",'.remove-button', function() {
                $(this).parents(':eq(1)').remove();
        });

        //add hit by clicking the green button
        $("#manuelleEingabeButtons").on( "click",'.add-button', function() {
                doAjaxManuelleEingabeInsert($(this).val());
        });

        $("#addDistance").change(function () {
            $("#manuelleEingabeButtons").html("");
            doAjaxManuelleEingabeSelect($("#addDistance").val());
        });

        $("#addStartnumberButton").click(function () {
            jQuery.ajax({
                url: "api/teilnehmer/startnummer/"+$("#addStartnumber").val(),
                method: "GET",
                dataType: "json",
                error: function (result) {
                    toastManager.show('Fehler: Teilnehmer nicht gefunden (113243537357)','Fehler', 'danger');
                },
                success: function (result) {
                    addButtonToDom(result,$("#addIdentnumber").val());
                    sortElements();
                }});
        });
    });

    function doAjaxManuelleEingabeSelect(id_var) {
            jQuery.ajax({
                url: "api/teilnehmer/strecke/"+id_var,
                method: "GET",
                dataType: "json",
                error: function (result) {
                    toastManager.show('Fehler beim Speichern Impuls (1343546335)','Fehler in der Abfrage', 'danger');
                },
                success: function (result) {
                    result.forEach(addButtonToDom);
                }});
    }

    function sortElements()
    {
        //sort elements inside sortable-numeric by data-sort attribute
        $('.sortable-numeric [data-sort]').sort(function(a, b) {
            if ($(a).data("sort") < $(b).data("sort")) {
            return -1;
            } else {
            return 1;
            }
        }).appendTo('.sortable-numeric');
    }
        
    function addButtonToDom(item, identNumber) {
        let inputField = jQuery("#manuelleEingabeButtons");
        //check if identNumber is set. When yes, create extra button
        let identNumberButton = identNumber != "" && !isNaN(identNumber) ? "<button type=\"button\" class=\"btn btn-secondary\">"+identNumber+"</button>" : "<button type=\"button\" class=\"btn btn-secondary\">"+item.startnummer+"</button>"
         //check if identNumber is set. When yes, creat identifyer for the sort method
        let identNumberSortIdentifyer = identNumber != "" && !isNaN(identNumber) ? "data-sort=\""+identNumber+"\"" : "data-sort=\""+item.startnummer+"\"";
        inputField.html(inputField.html()+"<div class=\"d-grid gap-2 col mx-auto\" "+identNumberSortIdentifyer+"><div class=\"btn-group mr-2\" role=\"group\">"+identNumberButton+"<button type=\"button\" name=\"sendImpulseEinlaufenData\" id=\"sendImpulseEinlaufenData"+item.id+"\" value=\""+item.id+"\" class=\"btn btn-success add-button\">"+item.vorname+"<br><span id=\"meter"+item.id+"\">"+item.meter+"</span>m</button><button type=\"button\" class=\"btn btn-danger remove-button\">X</button></div></div>");
    }

    //Disable Button for 20sec after a hit was added
    function disableEnableButton(jQueryButtonElement)
    {
        jQueryButtonElement.delay(2000).prop('disabled', true);
        setTimeout(() => {
            jQueryButtonElement.prop("disabled",false);
        }, "2000");
    }
        
    function doAjaxManuelleEingabeInsert(id_var) {
            var $meterPerHit =  {/literal} {$konfiguration->getRundenlaenge()};{literal} 
            var typ_var = "strecke";
            jQuery.ajax({
                url: "api/impulse/create",
                data: {typ: typ_var, id: id_var},
                method: "POST",
                dataType: "json",
                error: function (result) {
                    toastManager.show('Fehler beim Speichern Impuls (2321346557)', 'Fehler', 'danger');
                    
                },
                success: function (result) {
                    toastManager.show('Impuls gespeichert. Eingabe 20sec. gesperrt. ', 'Erfolgreich', 'success');
                    disableEnableButton(jQuery('#sendImpulseEinlaufenData'+id_var+''));
                    //Calculate meter dynamicly by addings $meterPerHit from configuration
                    $meterSpanField = $('#meter'+id_var);
                    $meterSpanField.html(parseInt($meterSpanField.html())+$meterPerHit);
                }});
        }
    {/literal}
</script>