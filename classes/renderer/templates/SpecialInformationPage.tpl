<div class="row mb-4">
    <div class="col-12 col-md-6 col-lg-3 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center">
                <div class="bg-primary rounded-circle text-white d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                    <i class="fa fa-2x fa-flag"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Meter</h6>
                    <h3 class="fw-bolder mb-0">{$meter}m</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center">
                <div class="bg-info rounded-circle text-white d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                    <i class="fa fa-2x fa-user"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Teilnehmer</h6>
                    <h3 class="fw-bolder mb-0">{$gemeldeteTeilnehmer}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center">
                <div class="bg-success rounded-circle text-white d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                    <i class="fa fa-2x fa-person-swimming"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">{$konfiguration->getStreckenart()}</h6>
                    <h3 class="fw-bolder mb-0">{$anzahlStreckenart}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center">
                <div class="bg-danger rounded-circle text-white d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                    <i class="fa fa-2x fa-money-bill"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Geld</h6>
                    <h3 class="fw-bolder mb-0">{$geld}€</h3>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0">
                <h4 class="card-title">Rekorde</h4>
            </div>
            <div class="card-body">
                <h6 class="mb-3">Veranstaltung</h6>     
                {if $konfiguration->getVeranstaltungsrekord() > 0}		
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted small">Rekord: {$konfiguration->getVeranstaltungsrekord()}m | Bisher: {$meter}m</span>
                    <span class="fw-bold text-success small">{$EA_H->getProzent($konfiguration->getVeranstaltungsrekord(),$meter)}%</span>
                </div>
                <div class="progress progress-striped active rounded-pill mb-4" style="height: 1.5rem;">
                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="{$EA_H->getProzent($konfiguration->getVeranstaltungsrekord(),$meter)}" aria-valuemin="0" aria-valuemax="100" style="width:{$EA_H->getProzent($konfiguration->getVeranstaltungsrekord(),$meter)}%"></div>
                </div>
                {/if}
                <h6 class="mb-3">Einzelstarter</h6>     
                {if $konfiguration->getTeilnehmerrekord() > 0}	
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted small">Rekord: {$konfiguration->getTeilnehmerrekord()}m | Bisher: {$besterTeilnehmer->getMeter()}m</span>
                    <span class="fw-bold text-success small">{$EA_H->getProzent($konfiguration->getTeilnehmerrekord(),$besterTeilnehmer->getMeter())}%</span>
                </div>
                <div class="progress progress-striped active rounded-pill mb-2" style="height: 1.5rem;">
                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="{$EA_H->getProzent($konfiguration->getTeilnehmerrekord(),$besterTeilnehmer->getMeter())}" aria-valuemin="0" aria-valuemax="100" style="width:{$EA_H->getProzent($konfiguration->getTeilnehmerrekord(),$besterTeilnehmer->getMeter())}%"></div>
                </div>
                {/if}
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12 col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0">
                <h4 class="card-title">Jüngster/Ältester Teilnehmer</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table width="100%" class="table table-striped table-hover mb-0" id="dataTables-example">
                        <thead class="text-muted">
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Strecke</th>
                                <th>Meter</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold">Jüngster Mann:</td>
                                <td>{if is_object($juengsterTeilnehmerMann) == true}{$juengsterTeilnehmerMann->getGesamtname()} ({$juengsterTeilnehmerMann->getGeburtsdatum()->format('Y')}){/if}</td>
                                <td>{if is_object($juengsterTeilnehmerMann) == true}{$juengsterTeilnehmerMann->getStrecke()->getBezKurz()}{/if}</td>
                                <td>{if is_object($juengsterTeilnehmerMann) == true}{$juengsterTeilnehmerMann->getMeter()}{/if}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Ältester Mann:</td>
                                <td>{if is_object($aeltesterTeilnehmerMann) == true}{$aeltesterTeilnehmerMann->getGesamtname()} ({$aeltesterTeilnehmerMann->getGeburtsdatum()->format('Y')}){/if}</td>
                                <td>{if is_object($aeltesterTeilnehmerMann) == true}{$aeltesterTeilnehmerMann->getStrecke()->getBezKurz()}{/if}</td>
                                <td>{if is_object($aeltesterTeilnehmerMann) == true}{$aeltesterTeilnehmerMann->getMeter()}{/if}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Jüngste Frau:</td>
                                <td>{if is_object($juengsterTeilnehmerFrau) == true}{$juengsterTeilnehmerFrau->getGesamtname()} ({$juengsterTeilnehmerFrau->getGeburtsdatum()->format('Y')}){/if}</td>
                                <td>{if is_object($juengsterTeilnehmerFrau) == true}{$juengsterTeilnehmerFrau->getStrecke()->getBezKurz()}{/if}</td>
                                <td>{if is_object($juengsterTeilnehmerFrau) == true}{$juengsterTeilnehmerFrau->getMeter()}{/if}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Älteste Frau:</td>
                                <td>{if is_object($aeltesterTeilnehmerFrau) == true}{$aeltesterTeilnehmerFrau->getGesamtname()} ({$aeltesterTeilnehmerFrau->getGeburtsdatum()->format('Y')}){/if}</td>
                                <td>{if is_object($aeltesterTeilnehmerFrau) == true}{$aeltesterTeilnehmerFrau->getStrecke()->getBezKurz()}{/if}</td>
                                <td>{if is_object($aeltesterTeilnehmerFrau) == true}{$aeltesterTeilnehmerFrau->getMeter()}{/if}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0">
                <h4 class="card-title">Teilnehmer nach Status</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table width="100%" class="table table-striped table-hover mb-0" id="dataTables-example">
                        <thead class="text-muted">
                            <tr>
                                <th>Strecke</th>
                                {foreach from=$stati key=schluessel item=status} 
                                <th title="{$status}">{$schluessel}</th>
                                {/foreach}	
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$strecken item=streckenvalue} 
                                <tr>
                                    <td class="fw-bold">{$streckenvalue->getBezKurz()}</td>
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
</div>

<div class="row mb-4">
    <div class="col-12 col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0">
                <h4 class="card-title">Medaillenspiegel</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table width="100%" class="table table-striped table-hover mb-0" id="dataTables-example">
                        <thead class="text-muted">
                            <tr>
                                <th>AK</th>
                                <th>U</th>
                                <th>B</th>
                                <th>S</th>
                                <th>G</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$medaillenspiegel key=medaillenkey item=medaillenvalue} 
                                <tr>
                                    <td class="fw-bold">{$medaillenvalue['AK_Name']}</td>
                                    <td>{if isset($medaillenvalue['U']) == true} {$medaillenvalue['U']} {else}  0 {/if}</td>
                                    <td>{if isset($medaillenvalue['B']) == true} {$medaillenvalue['B']} {else}  0 {/if}</td>
                                    <td>{if isset($medaillenvalue['S']) == true} {$medaillenvalue['S']} {else}  0 {/if}</td>
                                    <td>{if isset($medaillenvalue['G']) == true} {$medaillenvalue['G']} {else}  0 {/if}</td>
                                </tr>
                            {/foreach}	
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0">
                <h4 class="card-title">Gemeldete Teilnehmer pro Strecke und Altersklasse</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table width="100%" class="table table-striped table-hover mb-0" id="dataTables-example">
                        <thead class="text-muted">
                            <tr>
                                <th>Strecke</th>
                                <th colspan="3">Summe</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$StreckenTeilnehmerVerteilung key=schluessel item=value} 
                                <tr>
                                    <td class="fw-bold">{$value['Bezeichnung']} (Gesamt: {if isset($value['Summe']) == true} {$value['Summe']} {else} 0 {/if})</td>
                                    <td>{if isset($value['M']) == true} {$value['M']} {else} 0 {/if}</td>
                                    <td>{if isset($value['W']) == true} {$value['W']} {else} 0 {/if}</td>
                                    <td>{if isset($value['D']) == true} {$value['D']} {else} 0 {/if}</td>
                                </tr>
                            {/foreach}	
                            <tr>
                                <th class="text-muted">AK/Strecke</th>
                                {foreach from=$StreckenAltersklassenTeilnehmerVerteilung key=schluessel item=value} 
                                    <th colspan="3" class="text-muted">Strecke: {$value['Bezeichnung']}</th>
                                {/foreach}	
                            </tr>
                            <tr>
                                <th></th>
                                {foreach from=$StreckenAltersklassenTeilnehmerVerteilung key=schluessel item=value} 
                                    {foreach from=$geschlechter key=geschlechterkey item=geschlechtervalue}
                                    <th class="text-muted">{$geschlechtervalue}</th>
                                    {/foreach}	
                                {/foreach}	
                            </tr>
                            {foreach from=$altersklassen item=akvalue}
                                {assign var=i value=1}
                                <tr>
                                    {foreach from=$geschlechter key=geschlechterkey item=geschlechtervalue}	
                                        {foreach from=$StreckenAltersklassenTeilnehmerVerteilung key=schluessel item=value} 
                                            {if isset($value['Unterteilung'][{$akvalue->getId()}]['Unterteilung']['M']) == true || isset($value['Unterteilung'][{$akvalue->getId()}]['Unterteilung']['W']) == true}
                                                {if $geschlechtervalue == 'M' && $i == 1}	
                                                    <td class="fw-bold">{$akvalue->getAltersklasse()}</td> 
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
    </div>

</div>
<div class="row mb-4">
    <div class="col-12 col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0">
                <h4 class="card-title">Teilnehmende Vereine</h4>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    {if is_array($vereineLeistung) == true && count($vereineLeistung)> 0}		
                        {foreach from=$vereineLeistung key=schluessel item=value} 
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {$value->getVerein()}
                                <span class="badge bg-primary rounded-pill">{$value->getMitgliederList()->count()}</span>
                            </li>
                        {/foreach}
                    {/if}
                </ul>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0">
                <h4 class="card-title">Starter über 10.000m</h4>
            </div>
            <div class="card-body">
                {assign var="starter" value=$teilnehmerRepository->loadListSmartyZugriff(null, null, null,null,'gesamtplatz',200) nocache}
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-hover mb-0">
                        <thead class="text-muted">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>GebJ</th>
                                <th>Meter</th>
                                <th>{$konfiguration->getStreckenart()}</th>
                                <th>Geld</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$starter key=starterkey item=startervalue} 
                                <tr>
                                    <td class="fw-bold">{$startervalue->getGesamtplatz()}.</td>
                                    <td>{$startervalue->getGesamtname()}</td>
                                    <td>{$startervalue->getGeburtsdatum()->format("Y")}</td>
                                    <td>{$startervalue->getMeter()} </td>
                                    <td>{$startervalue->getStreckenart()}</td>
                                    <td>{$startervalue->getGeld()}</td>
                                </tr>
                            {/foreach}	
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- ########################################  -->
{foreach from=$StreckenAltersklassenTeilnehmerVerteilung key=schluessel item=value} 
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0">
        <h4 class="card-title">Plätze 1. - 3. Männer und Frauen (gesamt) für Strecke <span class="text-primary">{$value['Bezeichnung']}</span></h4>
    </div>
    <div class="card-body">
        <div class="row">
        {foreach from=$geschlechter key=geschlechterkey item=geschlechtervalue}
            <div class="col-12 col-lg-6 mb-4">
                {assign var="starter" value=$teilnehmerRepository->loadListSmartyZugriff($schluessel, null, $geschlechtervalue,'streckenplatz','streckenplatz') nocache}
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-hover mb-0">
                        <thead class="text-muted">
                            <tr>
                                <th colspan="5" class="bg-light">Geschlecht: {$geschlechtervalue}</th>
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
                                    <td class="fw-bold">{$startervalue->getStreckenplatz()}.</td>
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
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0">
        <h4 class="card-title">Plätze 1. - 3. nach Altersklassen für Strecke <span class="text-primary">{$value['Bezeichnung']}</span></h4>
    </div>
    <div class="card-body">
        <div class="row">
            {foreach from=$altersklassen item=akvalue}
                {foreach from=$geschlechter key=geschlechterkey item=geschlechtervalue}
                    {if isset($value['Unterteilung'][{$akvalue->getId()}]['Unterteilung'][{$geschlechtervalue}]) == true}	
                        <div class="col-12 col-lg-6 col-xl-4 mb-4">
                            {assign var="starter" value=$teilnehmerRepository->loadListSmartyZugriff($schluessel, $akvalue->getId(), $geschlechtervalue,'akplatz','akplatz') nocache}
                            <div class="table-responsive">
                                <table class="table table-sm table-striped table-hover mb-0">
                                    <thead class="text-muted">
                                        <tr>
                                            <th colspan="5" class="bg-light">AK: {$akvalue->getAltersklasseKurz()} | Geschl.: {$geschlechtervalue} | Anzahl: {$value['Unterteilung'][{$akvalue->getId()}]['Unterteilung'][{$geschlechtervalue}]}</th>
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
                                                <td class="fw-bold">{$startervalue->getAKPlatz()}.</td>
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
                    {/if}
                {/foreach}
            {/foreach}
        </div>
    </div>
</div>
{/foreach}