<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <h5 class="card-header">Selbstanmeldung zur Veranstaltung {$konfiguration->getVeranstaltungsname()}</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form role="form" name="SelbstanmeldungForm" id="SelbstanmeldungForm"
                              action="{$actionurl}" method="POST" class="form-horizontal">
                            <div class="row">
                                <div class="form-group col-sm-6">
                                {include file='templateInputElement.tpl' bezeichnung='Vorname' name='vorname' type='text' value=$vorname required=true }
                                </div>
                                <div class="form-group col-sm-6">
                                {include file='templateInputElement.tpl' bezeichnung='Name' name='name' type='text' value=$name required=true}
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    {include file='templateInputElement.tpl' bezeichnung='Geburtsdatum' name='geburtsdatum' type='date' value=$geburtsdatum required=true}
                                </div>	
                                <div class="form-group col-sm-6">
                                {include file='templateInputElement.tpl'  bezeichnung='Geschlecht'  name='geschlecht' type='select' required=true selectedElement=$geschlecht selectValueList=$geschlechter  }
                                </div>	

                            </div>
                            <div class="row">
                                {if $konfiguration->getMannschaften() == 1}	
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
                                {if $konfiguration->getStartgruppen() == true}						
                                    <div class="form-group col-sm-6">
                                    {include file='templateInputElement.tpl'  bezeichnung='Startgruppe'  name='startgruppe' type='select' required=true selectedElement=$startgruppen selectValueList=$teilnehmer->getStartgruppe() emptyElement=true  }
                                    </div>	
                                {/if}	
                            </div>
                            {if $konfiguration->getInputAdresse() eq true}
                                <div class="row">						
                                    <div class="form-group col-sm-6">
                                    {include file='templateInputElement.tpl' bezeichnung='PLZ' name='plz' type='number' value=$plz required=false minlength=4 maxlength=5}
                                    </div>	

                                    <div class="form-group col-sm-6">
                                    {include file='templateInputElement.tpl' bezeichnung='Wohnort' name='wohnort' type='text' value=$wohnort required=false}
                                    </div>		
                                </div>
                            {/if}

                            <div class="row">	
                                {if $konfiguration->getInputAdresse() eq true}					
                                    <div class="form-group col-sm-6">
                                    {include file='templateInputElement.tpl' bezeichnung='Straße' name='strasse' type='text' value=$tstrasse required=false}
                                    </div>	
                                {/if}
                            </div>

                            {if $konfiguration->getInputEmail() eq true}
                                <div class="row">								
                                    <div class="form-group col-sm-6">
                                    {include file='templateInputElement.tpl' bezeichnung='E-Mail' name='mail' type='email' value=$mail required=false}
                                    </div>
                                {/if}
                            </div>
                        <div class="row">	
                                <div class="form-group col-sm-6">
                                {include file='templateInputElement.tpl' bezeichnung='Teilnahmebedingungen' name='teilnahmebedingungen' type='checkbox' label='akzeptiert' required=true}
                                </div>
                        </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                {include file='templateInputElement.tpl' name='sendeSelbstanmeldung' label='Zur Veranstaltung anmelden' type='submit'}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
