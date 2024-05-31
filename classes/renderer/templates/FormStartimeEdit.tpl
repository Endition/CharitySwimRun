<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <h5 class="card-header">Altersklasse starten</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form role="form" name="StartAltersklasseForm" id="StartAltersklasseForm"
                              action="{$actionurl}&action=start&typ=altersklasse" method="POST" class="form-horizontal">
                            <div class="form-group col-sm-12">
                                <label class="form-label">Alterklasse</label>
                                <div class="col-sm-8">
                                    <select 
                                        name="altersklasse" id="altersklasse"
                                        class="form-control"  title="Bitte auswählen..."> 
                                        {foreach from=$altersklassen item=altersklasse}
                                            <option value="{$altersklasse->getId()}#MW">
                                                {$altersklasse->getAltersklasse()} (gesamt)
                                            </option>
                                            <option value="{$altersklasse->getId()}#M">
                                                {$altersklasse->getAltersklasse()} männlich
                                            </option>
                                            <option value="{$altersklasse->getId()}#W">
                                                {$altersklasse->getAltersklasse()} weiblich
                                            </option>
                                        {/foreach}
                                    </select>									
                                </div>
                            </div>	
                            <div class="form-group col-sm-12">
                                <label class="form-label"></label>
                                <div class="col-sm-8">
                                    <button type="submit" name="sendStartAltersklasseData" class="btn btn-primary">starten</button>
                                </div>
                            </div>	
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <h5 class="card-header">Strecke starten</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form role="form" name="StartStreckeForm" id="StartStreckeForm"
                              action="{$actionurl}&action=start&typ=strecke" method="POST" class="form-horizontal">
                            <div class="form-group col-sm-12">
                                <label class="form-label">Strecke</label>
                                <div class="col-sm-8">
                                    <select 
                                        name="strecke" id="strecke"
                                        class="form-control"  title="Bitte auswählen..."> 
                                        {foreach from=$strecken item=strecke}
                                            <option value="{$strecke->getId()}">
                                                {$strecke->getBezLang()}
                                            </option>
                                        {/foreach}
                                    </select>									
                                </div>
                            </div>	
                            <div class="form-group col-sm-12">
                                <label class="form-label"></label>
                                <div class="col-sm-8">
                                    <button type="submit" name="startStreckeData" class="btn btn-primary">starten</button>
                                </div>
                            </div>	
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <h5 class="card-header">Teilnehmer starten</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form role="form" name="StartTeilnehmerForm" id="StartTeilnehmerForm"
                              action="{$actionurl}&action=start&typ=teilnehmer" method="POST" class="form-horizontal">
                            <div class="form-group col-sm-12">
                                <label class="form-label">Teilnehmer</label>
                                <div class="col-sm-8">
                                    {if is_array($nichtgestarteteteilnehmer)  && count($nichtgestarteteteilnehmer) > 0}
                                        <select 
                                            name="teilnehmer[]" multiple="multiple"
                                            class="form-control"  title="Bitte auswählen..."> 
                                            {foreach from=$nichtgestarteteteilnehmer item=teilnehmerGestartet}
                                                <option value="{$teilnehmerGestartet->getId()}">
                                                    {$teilnehmerGestartet->getGesamtname()}  ({$teilnehmerGestartet->getStartnummer()})
                                                </option>
                                            {/foreach}
                                        </select>	
                                    {/if}								
                                </div>
                            </div>	
                            <div class="form-group col-sm-12">
                                <label class="form-label"></label>
                                <div class="col-sm-8">
                                    <button type="submit" name="startTeilnehmerData" class="btn btn-primary">starten</button>
                                </div>
                            </div>	
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <h5 class="card-header">{if  is_array($startgruppen) &&  count($startgruppen) > 0}Startgruppe starten{/if}	</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        {if  is_array($startgruppen) && count($startgruppen) > 0}
                            <form role="form" name="StartStartgruppeForm" id="StartStartgruppeForm"
                                  action="{$actionurl}&action=start&typ=startgruppe" method="POST" class="form-horizontal">
                                <div class="form-group col-sm-12">
                                    <label class="form-label">Startgruppe</label>
                                    <div class="col-sm-8">
                                        <select 
                                            name="startgruppe" id="startgruppe"
                                            class="form-control"  title="Bitte auswählen..."> 
                                            {foreach from=$startgruppen item=startgruppe}
                                                <option value="{$startgruppe}">
                                                    {$startgruppe}
                                                </option>
                                            {/foreach}
                                        </select>	
                                    </div>
                                </div>	
                                <div class="form-group col-sm-12">
                                    <label class="form-label"></label>
                                    <div class="col-sm-8">
                                        <button type="submit" name="startStartgruppeData" class="btn btn-primary">starten</button>
                                    </div>
                                </div>	
                            </form>
                        {/if}	
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <h5 class="card-header">Übersicht gestartete Teilnehmer</h5>
            <div class="card-body alert-success">
                <div class="row">
                    <div class="col-lg-12">
                    {if count($nichtgestarteteteilnehmer) > 0}
                        {if is_array($gestarteteteilnehmer) == true}
                            {foreach from=$gestarteteteilnehmer key=schluessel item=teilnehmerGestartet}
                                {$teilnehmerGestartet->getStartnummer()},
                            {/foreach}
                        {/if}	
                    {else}
                        Alle Teilnehmer sind gestartet    
                    {/if}	
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 ">
        <div class="card">
            <h5 class="card-header">Übersicht nicht-gestartete Teilnehmer</h5>
            <div class="card-body  alert-danger">
                <div class="row ">
                    <div class="col-lg-12 ">
                        {if is_array($nichtgestarteteteilnehmer) == true}
                            {foreach from=$nichtgestarteteteilnehmer key=schluessel item=teilnehmerNichtGestartet}
                                {$teilnehmerNichtGestartet->getStartnummer()},
                            {/foreach}
                        {/if}							
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <h5 class="card-header">Altersklasse Startzeit ändern</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form role="form" name="StreckenForm" id="StreckenForm"
                              action="{$actionurl}&action=editstartzeit&typ=altersklasse" method="POST" class="form-horizontal">
                            <div class="form-group col-sm-12">
                                <label class="form-label">Altersklasse</label>
                                <div class="col-sm-8">		
                                    <select 
                                        name="altersklasse"
                                        class="form-control"  title="Bitte auswählen..."> 
                                        {foreach from=$altersklassen item=altersklasse}
                                            <option value="{$altersklasse->getId()}#MW">
                                                {$altersklasse->getAltersklasse()} (gesamt)
                                            </option>
                                            <option value="{$altersklasse->getId()}#M">
                                                {$altersklasse->getAltersklasse()} männlich
                                            </option>
                                            <option value="{$altersklasse->getId()}#W">
                                                {$altersklasse->getAltersklasse()} weiblich
                                            </option>
                                        {/foreach}
                                    </select>						
                                </div>
                            </div>	
                            <div class="form-group col-sm-12">
                                <label class="form-label">Datum eingeben</label>
                                <div class="col-sm-8">	
                                    <input class="form-control" value="{$datum}"
                                           name="datum" value="{$datum}"
                                           data-parsley-pattern="/(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}/g"
                                           required
                                           >													
                                </div>
                            </div>	
                            <div class="form-group col-sm-12">
                                <label class="form-label">Zeit eingeben</label>
                                <div class="col-sm-8">	
                                    <input class="form-control" value="{$zeit}"
                                           name="zeit" 
                                           data-parsley-pattern="/([0-1][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]/g"
                                           required
                                           >													
                                </div>
                            </div>	
                            <div class="form-group col-sm-12">
                                <label class="form-label"></label>
                                <div class="col-sm-8">
                                    <button type="submit" name="sendImpulseZeitenData" class="btn btn-primary">eintragen</button>
                                </div>
                            </div>	
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <h5 class="card-header">Strecke Startzeit ändern</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form role="form" name="StreckenForm" id="StreckenForm"
                              action="{$actionurl}&action=editstartzeit&typ=strecke" method="POST" class="form-horizontal">
                            <div class="form-group col-sm-12">
                                <label class="form-label">Strecke</label>
                                <div class="col-sm-8">		
                                    <select 
                                        name="strecke"
                                        class="form-control"  title="Bitte auswählen..."> 
                                        {foreach from=$strecken item=strecke}
                                            <option value="{$strecke->getId()}">
                                                {$strecke->getBezLang()}
                                            </option>
                                        {/foreach}
                                    </select>					
                                </div>
                            </div>	
                            <div class="form-group col-sm-12">
                                <label class="form-label">Datum eingeben</label>
                                <div class="col-sm-8">	
                                    <input class="form-control" value="{$datum}"
                                           name="datum" value="{$datum}"
                                           data-parsley-pattern="/(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}/g"
                                           required
                                           >													
                                </div>
                            </div>	
                            <div class="form-group col-sm-12">
                                <label class="form-label">Zeit eingeben</label>
                                <div class="col-sm-8">	
                                    <input class="form-control" value="{$zeit}"
                                           name="zeit" 
                                           data-parsley-pattern="/([0-1][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]/g"
                                           required
                                           >													
                                </div>
                            </div>	
                            <div class="form-group col-sm-12">
                                <label class="form-label"></label>
                                <div class="col-sm-8">
                                    <button type="submit" name="sendImpulseZeitenData" class="btn btn-primary">eintragen</button>
                                </div>
                            </div>	
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <h5 class="card-header">Teilnehmer Startzeit ändern</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form role="form" name="StreckenForm" id="StreckenForm"
                              action="{$actionurl}&action=editstartzeit&typ=teilnehmer" method="POST" class="form-horizontal">
                            <div class="form-group col-sm-12">
                                <label class="form-label">Teilnehmer</label>
                                <div class="col-sm-8">		
                                    {if is_array($gestarteteteilnehmer)  && count($gestarteteteilnehmer) > 0}
                                        <select 
                                            name="teilnehmer[]" name="teilnehmer" multiple="multiple"
                                            class="form-control"  title="Bitte auswählen..."> 
                                            {foreach from=$gestarteteteilnehmer item=teilnehmerGestartet}
                                                <option value="{$teilnehmerGestartet->getId()}">
                                                    {$teilnehmerGestartet->getGesamtname()} ({$teilnehmerGestartet->getStartnummer()})
                                                </option>
                                            {/foreach}
                                        </select>	
                                    {/if}				
                                </div>
                            </div>	
                            <div class="form-group col-sm-12">
                                <label class="form-label">Datum eingeben</label>
                                <div class="col-sm-8">	
                                    <input class="form-control" value="{$datum}"
                                           name="datum" value="{$datum}"
                                           data-parsley-pattern="/(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}/g"
                                           required
                                           >													
                                </div>
                            </div>	
                            <div class="form-group col-sm-12">
                                <label class="form-label">Zeit eingeben</label>
                                <div class="col-sm-8">	
                                    <input class="form-control" value="{$zeit}"
                                           name="zeit" 
                                           data-parsley-pattern="/([0-1][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]/g"
                                           required
                                           >													
                                </div>
                            </div>	
                            <div class="form-group col-sm-12">
                                <label class="form-label"></label>
                                <div class="col-sm-8">
                                    <button type="submit" name="sendImpulseZeitenData" class="btn btn-primary">eintragen</button>
                                </div>
                            </div>	
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <h5 class="card-header">{if is_array($startgruppen) && count($startgruppen) > 0}Startgruppe Startzeit ändern{/if}	</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        {if is_array($startgruppen) &&  count($startgruppen) > 0}
                            <form role="form" name="StreckenForm" id="StreckenForm"
                                  action="{$actionurl}&action=editstartzeit&typ=startgruppe" method="POST" class="form-horizontal">
                                <div class="form-group col-sm-12">
                                    <label class="form-label">Startgruppe</label>
                                    <div class="col-sm-8">		
                                        <select 
                                            name="startgruppe"
                                            class="form-control"  title="Bitte auswählen..."> 
                                            {foreach from=$startgruppen item=startgruppe}
                                                <option value="{$startgruppe}">
                                                    {$startgruppe}
                                                </option>
                                            {/foreach}
                                        </select>					
                                    </div>
                                </div>	
                                <div class="form-group col-sm-12">
                                    <label class="form-label">Datum eingeben</label>
                                    <div class="col-sm-8">	
                                        <input class="form-control" value="{$datum}"
                                               name="datum" value="{$datum}"
                                               data-parsley-pattern="/(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}/g"
                                               required
                                               >													
                                    </div>
                                </div>	
                                <div class="form-group col-sm-12">
                                    <label class="form-label">Zeit eingeben</label>
                                    <div class="col-sm-8">	
                                        <input class="form-control" value="{$zeit}"
                                               name="zeit" 
                                               data-parsley-pattern="/([0-1][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]/g"
                                               required
                                               >													
                                    </div>
                                </div>	
                                <div class="form-group col-sm-12">
                                    <label class="form-label"></label>
                                    <div class="col-sm-8">
                                        <button type="submit" name="sendImpulseZeitenData" class="btn btn-primary">eintragen</button>
                                    </div>
                                </div>	
                            </form>
                        {/if}	
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
