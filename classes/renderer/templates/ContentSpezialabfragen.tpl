<div class="row">
    <div class="col-3">
        <div class="card">
        <div class="card-body">
        Meter:</b><br>  {$meter}m
        </div>  
        </div>
    </div>
    <div class="col-3">
        <div class="card">
            <div class="card-body">
                 Teilnehmer:</b> <br>  {$gemeldeteTeilnehmer}
            </div>  
        </div>
    </div>
    <div class="col-3">
        <div class="card">
            <div class="card-body">
                {$konfiguration->getStreckenart()}:</b><br>   {$anzahlStreckenart}	
            </div>  
        </div>
    </div>
    <div class="col-3">
        <div class="card">
        <div class="card-body">
        Geld:</b> <br>   {$geld} Euro  
        </div>  
        </div>           
    </div>
</div>
<div class="row">
    <div class="col-12">
                <div class="card">
                    <h5 class="card-header">Rekorde</h5>
                    <div class="card-body">
                    <b>Veranstaltung:</b><br>     
                    {if $konfiguration->getVeranstaltungsrekord() > 0}		
                        <div class="progress">
                            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" 
                                aria-valuenow="{$EA_H->getProzent($konfiguration->getVeranstaltungsrekord(),$meter)}" aria-valuemin="0" aria-valuemax="100" style="width:{$EA_H->getProzent($konfiguration->getVeranstaltungsrekord(),$meter)}%">
                                Rekord:{$konfiguration->getVeranstaltungsrekord()}|Bisher:{$meter}|{$EA_H->getProzent($konfiguration->getVeranstaltungsrekord(),$meter)}%
                            </div>
                    </div>
                    {/if}
                    <b>Einzelstarter:</b><br>     
                    <div class="progress">
                    {if $konfiguration->getTeilnehmerrekord() > 0}	
                        <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" 
                            aria-valuenow="{$EA_H->getProzent($konfiguration->getTeilnehmerrekord(),$besterTeilnehmer->getMeter())}" aria-valuemin="0" aria-valuemax="100" style="width:{$EA_H->getProzent($konfiguration->getTeilnehmerrekord(),$besterTeilnehmer->getMeter())}%">
                            Rekord:{$konfiguration->getTeilnehmerrekord()}|Bisher:{$besterTeilnehmer->getMeter()}|{$EA_H->getProzent($konfiguration->getTeilnehmerrekord(),$besterTeilnehmer->getMeter())}%
                        </div>
                    {/if}
                    </div>
                </div>
            </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <h5 class="card-header">Jüngster/Ältester Teilnehmer</h5>
            <div class="card-body">
                        <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <tr>
                                <td></td>
                                <td>Name</td>
                                <td>Strecke</td>
                                <td>Meter</td>
                            </tr>
                            <tr>
                                <td>Jüngster Mann:</td>
                                <td>{if is_object($juengsterTeilnehmerMann) == true}{$juengsterTeilnehmerMann->getGesamtname()} ({$juengsterTeilnehmerMann->getGeburtsdatum()->format('Y')}){/if}</td>
                                <td>{if is_object($juengsterTeilnehmerMann) == true}{$juengsterTeilnehmerMann->getStrecke()->getBezKurz()}{/if}</td>
                                <td>{if is_object($juengsterTeilnehmerMann) == true}{$juengsterTeilnehmerMann->getMeter()}{/if}</td>
                            </tr>
                            <tr>
                                <td>Ältester Mann:</td>
                                <td>{if is_object($aeltesterTeilnehmerMann) == true}{$aeltesterTeilnehmerMann->getGesamtname()} ({$aeltesterTeilnehmerMann->getGeburtsdatum()->format('Y')}){/if}</td>
                                <td>{if is_object($aeltesterTeilnehmerMann) == true}{$aeltesterTeilnehmerMann->getStrecke()->getBezKurz()}{/if}</td>
                                <td>{if is_object($aeltesterTeilnehmerMann) == true}{$aeltesterTeilnehmerMann->getMeter()}{/if}</td>
                            </tr>
                            <tr>
                                <td>Jüngste Frau:</td>
                                <td>{if is_object($juengsterTeilnehmerFrau) == true}{$juengsterTeilnehmerFrau->getGesamtname()} ({$juengsterTeilnehmerFrau->getGeburtsdatum()->format('Y')}){/if}</td>
                                <td>{if is_object($juengsterTeilnehmerFrau) == true}{$juengsterTeilnehmerFrau->getStrecke()->getBezKurz()}{/if}</td>
                                <td>{if is_object($juengsterTeilnehmerFrau) == true}{$juengsterTeilnehmerFrau->getMeter()}{/if}</td>
                            </tr>
                            <tr>
                                <td>Älteste Frau:</td>
                                <td>{if is_object($aeltesterTeilnehmerFrau) == true}{$aeltesterTeilnehmerFrau->getGesamtname()} ({$aeltesterTeilnehmerFrau->getGeburtsdatum()->format('Y')}){/if}</td>
                                <td>{if is_object($aeltesterTeilnehmerFrau) == true}{$aeltesterTeilnehmerFrau->getStrecke()->getBezKurz()}{/if}</td>
                                <td>{if is_object($aeltesterTeilnehmerFrau) == true}{$aeltesterTeilnehmerFrau->getMeter()}{/if}</td>
                            </tr>
                        </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <h5 class="card-header">Teilnehmer nach Status</h5>
            <div class="card-body">
                        <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th></th>
                                        {foreach from=$stati key=schluessel item=status} 
                                        <th title="{$status}">{$schluessel}</th>
                                        {/foreach}	
                                </tr>
                            </thead>
                            <tbody>
                                {foreach from=$strecken item=streckenvalue} 
                                    <tr>
                                        <td>{$streckenvalue->getBezKurz()}</td>
                                        {if isset($statiVerteilung[{$streckenvalue->getId()}]) }	
                                            {foreach from=$statiVerteilung[{$streckenvalue->getId()}] item=value} 
                                                <td>{$value}</td>
                                            {/foreach}	
                                         {/if}						
                                    </tr>
                                {/foreach}	
                            </tbody>
                        </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <h5 class="card-header">Medaillenspiegel</h5>
            <div class="card-body">

                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <tr>
                                    <th>AK</th>
                                    <th>U</th>
                                    <th>B</th>
                                    <th>S</th>
                                    <th>G</th>
                                </tr>
                                {foreach from=$medaillenspiegel key=medaillenkey item=medaillenvalue} 
                                    <tr>
                                        <td>{$medaillenvalue['AK_Name']}</td>
                                        <td>{if isset($medaillenvalue['U']) == true} {$medaillenvalue['U']} {else}  0 {/if}</td>
                                        <td>{if isset($medaillenvalue['B']) == true} {$medaillenvalue['B']} {else}  0 {/if}</td>
                                        <td>{if isset($medaillenvalue['S']) == true} {$medaillenvalue['S']} {else}  0 {/if}</td>
                                        <td>{if isset($medaillenvalue['G']) == true} {$medaillenvalue['G']} {else}  0 {/if}</td>
                                    </tr>
                                {/foreach}	
                            </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <h5 class="card-header">Gemeldete Teilnehmer pro Strecken und Altersklasse</h5>
            <div class="card-body">

                            <table width="100%"
                                   class="table table-striped table-bordered table-hover"
                                   id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>Strecke</th>
                                        <th colspan="3">Summe</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    

                                        {foreach from=$StreckenTeilnehmerVerteilung key=schluessel item=value} 
                                            <tr>
                                            <td>{$value['Bezeichnung']} (Gesamt: {if isset($value['Summe']) == true}	 {$value['Summe']} {else} 0 {/if})</td>
                                            <td>{if isset($value['M']) == true}	 {$value['M']} {else} 0 {/if}</td>
                                            <td>{if isset($value['W']) == true}	 {$value['W']} {else} 0 {/if}</td>
                                            <td>{if isset($value['D']) == true}	 {$value['D']} {else} 0 {/if}</td>
                                                </tr>
                                        {/foreach}	
                                    
                                    <tr>
                                        <th>AK/Strecke</th>
                                        {foreach from=$StreckenAltersklassenTeilnehmerVerteilung key=schluessel item=value} 
                                            <th colspan="3">Strecke: {$value['Bezeichnung']}</th>
                                        {/foreach}	

                                    </tr>
                                    <tr>
                                        <th></th>
                                            {foreach from=$StreckenAltersklassenTeilnehmerVerteilung key=schluessel item=value} 
                                                {foreach from=$geschlechter key=geschlechterkey item=geschlechtervalue}
                                                <th>{$geschlechtervalue}</th>
                                                {/foreach}	
                                            {/foreach}	

                                    </tr>
                                 
                                    {foreach from=$altersklassen item=akvalue}
                                        <tr>
                                     
                                            {foreach from=$geschlechter key=geschlechterkey item=geschlechtervalue}	
                                                {foreach from=$StreckenAltersklassenTeilnehmerVerteilung key=schluessel item=value} 
                                                    {if isset($value['Unterteilung'][{$akvalue->getId()}]['Unterteilung']['M']) == true || isset($value['Unterteilung'][{$akvalue->getId()}]['Unterteilung']['W']) == true}
                                                        {if $geschlechtervalue == 'M' && $i == 1}	<!-- Dient nur dazu das die erste Spalte nur einmal generiert wird-->
                                                            <td>{$akvalue->getAltersklasse()} </td> 
                                                         {assign var=i value=$i+1}
                                                        {/if}
                                                        {if isset($value['Unterteilung'][{$akvalue->getId()}]['Unterteilung'][{$geschlechtervalue}]) == true}	
                                                            <td>{$value['Unterteilung'][{$akvalue->getId()}]['Unterteilung'][{$geschlechtervalue}]}</td>	
                                                        {else}
                                                            <td>0</td>	
                                                        {/if}
                                                    {/if}
                                                {/foreach}	
                                                
                                            {/foreach}
                                            
                                        </tr>	
                                    {/foreach}
                                </tbody>
                            </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <h5 class="card-header">Teilnehmende Vereine</h5>
            <div class="card-body">
                        <ol>
                            {if is_array($vereineLeistung) == true && count($vereineLeistung)> 0}		
                                {foreach from=$vereineLeistung key=schluessel item=value} 
                                    <li>{$value->getVerein()} ({$value->getMitgliederList()->count()})</li>
                                    {/foreach}
                                {/if}
                        </ol>


            </div>
        </div>
    </div>
</div>
<!-- ########################################  -->
{foreach from=$StreckenAltersklassenTeilnehmerVerteilung key=schluessel item=value} 
            <div class="card">
                <h5 class="card-header">Plätze 1. - 3. Männer und Frauen (gesamt) für Strecke <b>{$value['Bezeichnung']}</b></h5>
                <div class="card-body">

                    <div class="row">
                    {foreach from=$geschlechter key=geschlechterkey item=geschlechtervalue}
                    
                            <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12">
                                            {assign var="starter" value=$teilnehmerRepository->loadListSmartyZugriff($schluessel, null, $geschlechtervalue,'streckenplatz','streckenplatz') nocache}
                                            <div class="table-responsive">
                                                <table class="table table-sm table-striped table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="5"><b> Geschlecht:</b>  {$geschlechtervalue} </td>
                                                        </tr>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Name</th>
                                                            <th>Meter</th>
                                                            <th>{$konfiguration->getStreckenart()}</th>
                                                            <th>Geld</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        {foreach from=$starter key=starterkey item=startervalue} 
                                                            <tr>
                                                                <td>{$startervalue->getStreckenplatz()}.</td>
                                                                <td>{$startervalue->getGesamtname()}</td>
                                                                <td>{$startervalue->getMeter()} </td>
                                                                <td>{$startervalue->getStreckenart()}</td>
                                                                <td>{$startervalue->getGeld()}</td>
                                                            </tr>
                                                        {/foreach}	
                                                    </tbody>
                                                </table>
                                            </div>
                            </div>
                        
                    {/foreach}
                    </div>
                </div>
            </div>
  
{/foreach}
<!-- ########################################  -->

{foreach from=$StreckenAltersklassenTeilnehmerVerteilung key=schluessel item=value} 
            <div class="card">
                <h5 class="card-header">Plätze 1. - 3. nach Altersklassen für Strecke <b>{$value['Bezeichnung']}</b></h5>
                <div class="card-body">
                   
                        <div class="row">
                            {foreach from=$altersklassen item=akvalue}
                                {foreach from=$geschlechter key=geschlechterkey item=geschlechtervalue}
                                    {if isset($value['Unterteilung'][{$akvalue->getId()}]['Unterteilung'][{$geschlechtervalue}]) == true}	
                                        <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12">
                                                        {assign var="starter" value=$teilnehmerRepository->loadListSmartyZugriff($schluessel, $akvalue->getId(), $geschlechtervalue,'akplatz','akplatz') nocache}
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-striped table-bordered">
                                                            <tr>
                                                                <th colspan="5"> <b>Altersklasse:</b>  {$akvalue->getAltersklasse()}; <b> Geschlecht:</b>  {$geschlechtervalue}; <b> Anzahl:</b>  {$value['Unterteilung'][{$akvalue->getId()}]['Unterteilung'][{$geschlechtervalue}]}  </td>
                                                            </tr>

                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Name</th>
                                                                    <th>Meter</th>
                                                                    <th>{$konfiguration->getStreckenart()}</th>
                                                                    <th>Geld</th>
                                                                </tr>
                                                                {foreach from=$starter key=starterkey item=startervalue} 
                                                                    <tr>
                                                                        <td>{$startervalue->getAKPlatz()}.</td>
                                                                        <td>{$startervalue->getGesamtname()}</td>
                                                                        <td>{$startervalue->getMeter()} </td>
                                                                        <td>{$startervalue->getStreckenart()}</td>
                                                                        <td>{$startervalue->getGeld()}</td>
                                                                    </tr>
                                                                {/foreach}	
                                                            </table>	
                                        </div>	 </div>	
                                    {/if}
                                {/foreach}
                            {/foreach}
                        </div>
                  
                </div>
            </div>

{/foreach}	
</div>