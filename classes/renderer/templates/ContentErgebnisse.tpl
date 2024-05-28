<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <h5 class="card-header">Ergebnisliste auswählen</h5>
            <div class="card-body">
                        <form role="form" name="SelectErgebnislisteForm" id="SelectErgebnislisteForm" method="POST" action="{$actionurl}">
                              <div class="row">
                                <div class="col-sm-3">
                                    <select 
                                        name="ergebnisliste_typ"
                                        class="form-control"> 
                                        {if $filter['typ'] != null}
                                            <option selected="selected" value="{$filter['typ']}">{$filter['typ']}</option> 
                                        {/if}
                                        <option value="Einzelstarter">Einzelstarter</option>
                                        <option value="Mannschaften">Mannschaften</option>
                                        <option value="Vereine">Vereine</option>
                                        <option value="specialEvaluation">Spnderauswertung</option>
                                    </select>									
                                </div>
                                <div class="col-sm-3">
                                <select 
                                    name="specialEvaluation"
                                    class="form-control"> 
                                    <option value="">ggf. auswählens</option>
                                    {html_options options=$specialEvaluationList} 
                                </select>									
                            </div>
                                <div class="col-sm-3">
                                    <button type="submit" name="sendSelectErgebnislisteData"  class="btn btn-primary">Anzeigen</button>
                                </div>
                            </div>
                        </form>
            </div>
        </div>
    </div>
</div>						
<div class="row">
    <div class="col-lg-12">
        <div class="card">
        <div class="card-header">
            <h5>Ergebnisliste anzeigen</h5>
            <p>Der Haupt-"Platz" ist nach Geschlecht unterschieden. In der Standardansicht kann es daher drei Mal den 1. Platz geben (M/W/D). </p>
        </div>
           
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">	
                            {if $filter['typ'] == "Mannschaften"}
                                {include file="DisplayTabelleErgebnisseMannschaften.tpl"}
                            {elseif $filter['typ'] == "Vereine"}
                                {include file="DisplayTabelleErgebnisseVereine.tpl"}
                            {else}
                                {include file="DisplayTabelleErgebnisseTeilnehmer.tpl"}
                            {/if}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
    {include file="ContentListenDrucken.tpl"}