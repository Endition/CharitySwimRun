{include file='StarterFormSearch.tpl'}


<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5>Teilnehmer anlegen </h5>
                <a href="{$actionurl}" class="btn btn-warning btn-sm float-right">Reset für weitere Anmeldung</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form role="form" name="TeilnehmerForm" id="TeilnehmerForm"
                              action="{$actionurl}{if $edit == true}&action=edit{/if}" method="POST" class="form-horizontal">

                            <div class="row">
                                {include file='templateInputElement.tpl' name='id' type='hidden' value=$teilnehmer->getId() required=true}
                                <div class="form-group col-sm-6">
                                    {include file='templateInputElement.tpl' bezeichnung='Startnummer' name='startnummer' type='number' value=$teilnehmer->getStartnummer() required=true id="Startnummer" step=1  onkeyup="document.getElementById('Transponder').value = document.getElementById('Startnummer').value;"}
                                </div>

                                {if $konfiguration->getTransponder() eq true}	
                                    <div class="form-group col-sm-6">
                                    {include file='templateInputElement.tpl' bezeichnung='Transponder' name='transponder' type='number' value=$teilnehmer->getTransponder() required=false id="Transponder" step=1}
                                    </div>
                                {/if}	
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    {include file='templateInputElement.tpl' bezeichnung='Vorname' name='vorname' type='text' value=$teilnehmer->getVorname() required=true }
                                </div>
                                <div class="form-group col-sm-6">
                                    {include file='templateInputElement.tpl' bezeichnung='Name' name='name' type='text' value=$teilnehmer->getName() required=true}
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    {include file='templateInputElement.tpl' bezeichnung='Geburtsdatum' name='geburtsdatum' type='date' value=$teilnehmer->getGeburtsdatum()->format('Y-m-d') required=true}
                                </div>	
                                <div class="form-group col-sm-6">
                                    {include file='templateInputElement.tpl'  bezeichnung='Geschlecht'  name='geschlecht' type='select' required=true selectedElement=$teilnehmer->getGeschlecht() selectValueList=$geschlechter  }
                                </div>	

                            </div>
                            <div class="row">
                                {if $konfiguration->getMannschaften() eq 1}	
                                    <div class="form-group col-sm-6">
                                    {include file='templateInputElement.tpl'  bezeichnung='Mannschaft'  name='mannschaften' type='select' selectedElement=$mannschaft selectValueList=$mannschaften emptyElement=true }
                                    </div>	
                                {/if}						
                                <div class="form-group col-sm-6">
                                    {include file='templateInputElement.tpl' name='vereinid' id="Vereinid" type='hidden' value=$vereinid }
                                    {include file='templateInputElement.tpl' bezeichnung='Verein' name='verein' id="Verein" type='text' value=$verein required=false}
                                                                    <script type="text/javascript">
                                                                    $(document).ready(function($){ 
                                                                       $("#Verein").autocomplete({
                                                                           source: function(request, response) {
                                                                               $.ajax({
                                                                                   url: "api/verein/search/"+$("#Verein").val(),
                                                                                   dataType: "json",
                                                                                   success: function(data) {
                                                                                       response(data);
                                                                                   }
                                                                               });
                                                                           },
                                                                           minLength: 1,
                                                                           select: function(event, ui) {
                                                                                $("#Verein").val(ui.item.label);
                                                                                $("#Vereinid").val(ui.item.value);
                                                                                return false;
                                                                            }
                                                                           });
                                                                     });
                                                                </script>
                                </div>	
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                {include file='templateInputElement.tpl'  bezeichnung='Strecke'  name='strecke' type='select' required=true selectedElement=$strecke selectValueList=$strecken  }
                                </div>	
                                {if $konfiguration->getStartgruppen() eq true}						
                                    <div class="form-group col-sm-6">
                                    {include file='templateInputElement.tpl'  bezeichnung='Startgruppe'  name='startgruppe' type='select' required=true selectedElement=$startgruppen selectValueList=$teilnehmer->getStartgruppe() emptyElement=true  }
                                    </div>	
                                {/if}	
                            </div>
                            {if $konfiguration->getInputAdresse() eq true}
                                <div class="row">						
                                    <div class="form-group col-sm-6">
                                        {include file='templateInputElement.tpl' bezeichnung='PLZ' name='plz' type='number' value=$teilnehmer->getPlz() required=false minlength=4 maxlength=5}
                                    </div>	

                                    <div class="form-group col-sm-6">
                                        {include file='templateInputElement.tpl' bezeichnung='Wohnort' name='wohnort' type='text' value=$teilnehmer->getWohnort() required=false}
                                    </div>		
                                </div>
                            {/if}

                            <div class="row">	
                                {if $konfiguration->getInputAdresse() eq true}					
                                    <div class="form-group col-sm-6">
                                    {include file='templateInputElement.tpl' bezeichnung='Straße' name='strasse' type='text' value=$teilnehmer->getStrasse() required=false}
                                    </div>	
                                {/if}
                                <div class="form-group col-sm-6">
                                    {include file='templateInputElement.tpl'  bezeichnung='Status'  name='status' type='select' required=false selectedElement=$teilnehmer->getStatus() selectValueList=$stati  }
                                </div>	
                            </div>

                            {if $konfiguration->getInputEmail() eq true}
                                <div class="row">								
                                    <div class="form-group col-sm-6">
                                        {include file='templateInputElement.tpl' bezeichnung='E-Mail' name='mail' type='email' value=$teilnehmer->getMail() required=false}
                                    </div>
                                {/if}
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    {include file='templateInputElement.tpl' name='sendTeilnehmerData' type='submit'}
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="form-label"></label>
                                    <div class="col-sm-8">
                                        {if $teilnehmer->getId() > 0}
                                            <button type="submit" formaction="{$actionurl}&action=delete"
                                                    class="btn btn-danger" onClick="return confirm('Soll der Teilnehmer wirklich gelöscht werden?')">Löschen</button>
                                        {/if}
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
