
        <div class="card">
            <h5 class="card-header">Status verwalten</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                    <table 
                    class="table table-striped table-bordered table-hover"
                    data-toggle="table"
                      data-search="true"
                      data-pagination="true">
                            <thead>
                                <tr>
                                    <th>StNr</th>
                                        {if $konfiguration->getTransponder() eq true}	
                                        <th>TP</th>
                                        {/if}
                                    <th>Name</th>
                                    <th>JG</th>
                                    <th>Strecke</th>
                                    <th>MW</th>
                                    <th>bezahlt</th>
                                    <th>Startunt. abgeholt</th>
                                    <th>gestartet</th>
                                    <th>auf der Strecke</th>
                                    <th>gültige Buchung</th>
                                    <th>TP zurück</th>
                                </tr>
                            </thead>
                            <form role="form" name="FehlbuchungenForm" id="FehlbuchungenForm"
                                  action="{$actionurl}" method="POST">
                                <tbody>
                                    {foreach from=$teilnehmer key=schluessel item=value}
                                        <tr class="odd gradeX">
                                            <td>{$value->getStartnummer()}</td>
                                            {if $konfiguration->getTransponder() eq true}	
                                                <td>{$value->getTransponder()}</td>
                                            {/if}
                                            <td><a href="index.php?doc=teilnehmer&action=search&id={$value->getId()}">{$value->getGesamtname()}</a></td>
                                            <td>{$value->getGeburtsdatum()->format("Y")}</td>
                                            <td>{$value->getStrecke()->getBezKurz()}</td>
                                            <td>{$value->getGeschlecht()}</td>
                                            <td><input type="checkbox" name="Status_20_{$value->getId()}" title="" id="Status_20_{$value->getId()}" {if $value->getStatus() >= 20} checked="checked" {/if} value="20" onclick="saveStatus({$value->getId()}, 'Status_20_{$value->getId()}')"/></td>
                                            <td><input type="checkbox" name="Status_30_{$value->getId()}" title="" id="Status_30_{$value->getId()}" {if $value->getStatus() >= 30} checked="checked" {/if} value="30" onclick="saveStatus({$value->getId()}, 'Status_30_{$value->getId()}')" /></td>
                                            <td><input type="checkbox" name="Status_50_{$value->getId()}" title="" id="Status_50_{$value->getId()}" {if $value->getStatus() >= 50} checked="checked" {/if} value="50" onclick="saveStatus({$value->getId()}, 'Status_50_{$value->getId()}')" /></td>
                                            <td><input type="checkbox" name="Status_70_{$value->getId()}" title="" id="Status_70_{$value->getId()}" {if $value->getStatus() >= 70} checked="checked" {/if} value="70" onclick="saveStatus({$value->getId()}, 'Status_70_{$value->getId()}')" /></td>
                                            <td><input type="checkbox" name="Status_90_{$value->getId()}" title="" id="Status_90_{$value->getId()}" {if $value->getStatus() >= 90} checked="checked" {/if} value="90" onclick="saveStatus({$value->getId()}, 'Status_90_{$value->getId()}')" /></td>
                                            <td><input type="checkbox" name="Status_99_{$value->getId()}" title="" id="Status_99_{$value->getId()}" {if $value->getStatus() >= 99} checked="checked" {/if} value="99" onclick="saveStatus({$value->getId()}, 'Status_99_{$value->getId()}')" /></td>
                                        </tr>
                                    {/foreach}
                                </tbody>
                            </form>
                        </table>   					
                    </div>
                </div>
            </div>
        </div>


<script type="text/javascript">
    {literal}
//Alle Checkboxen in der Zeile durchlaufen und Zeilen nach dem gewählten Status anpassen
        function checkAndUpdateGUI(element_id, status_var) {
            var stati = [10, 20, 30, 50, 70, 90, 99];
            var stringsplit = stringsplit = element_id.split("_");
            var elementname = "";

            for (index = 0; index < stati.length; ++index) {
                elementname = stringsplit[0] + "_" + stati[index] + "_" + stringsplit[2];
                if (jQuery('#' + elementname).val() <= status_var) {
                    jQuery('#' + elementname).prop('checked', true);
                } else {
                    jQuery('#' + elementname).prop('checked', false);
                }
            }
        }


        function saveStatus(id_var, element_id) {
            var stati = [10, 20, 30, 50, 70, 90, 99];
            var status_var = null;
            //prüfen ob gecheckt, oder endcheckt wurde, demensprechend Statuswert änderung danach absenden
            if (jQuery("#" + element_id).is(":checked")) {
                status_var = jQuery('#' + element_id + '').val();
            } else {
                for (index = 0; index < stati.length; ++index) {
                    if (jQuery('#' + element_id + '').val() == stati[index]) {
                        status_var = stati[index - 1];
                        break;
                    }
                }
            }

            //Ajax abfeuern.
            jQuery.ajax({
                url: "api/status/update",
                data: {status: status_var, id: id_var},
                method: "POST",
                dataType: "json",
                error: function (result) {
                    toastManager.show('Fehler beim Speichern Impuls (213324357)', 'Fehler', 'danger');
                },
                success: function (result) {
                    toastManager.show('Status gespeichert', 'Erfolgreich', 'success');
                    checkAndUpdateGUI(element_id, status_var);
                }});

        }

    {/literal}

</script>   