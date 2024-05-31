{foreach from=$ergebnisse key=schluessel item=value}
    <b>{$value->getVerein()}</b> ({$value->getMitgliederList()->count()} Mitglieder)<br/>
    <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="dataTables-ergebnisse">
    <thead>
        <tr>
                <th width="5%"><b>Platz</b></th>
                <th width="6%"><b>St.Platz</b></th>
                <th width="6%"><b>AKPlatz</b></th>
                <th width="5%"><b>StNr</b></th>
                <th width="20%"><b>Name</b></th>
                <th width="10%"><b>AK</b></th>
                <th width="20%"><b>Verein</b></th>
                <th width="6%"><b>Strecke</b></th>
                    <th width="6%"><b>{$konfiguration->getStreckenart()}</b></th>
                    <th width="5%"><b>Meter</b></th>
                    <th width="5%"><b>Geld</b></th>					 				
        </tr>
    </thead>
    <tbody>
         {assign var="i" value=1 nocache}
       {foreach from=$value->getMitgliederList()  key=schluessel2 item=value2}
                <tr {if ($i % 2) != 0} style="background-color:#dce0e8;"{/if}>
                    <td width="5%">{$value2->getGesamtplatz()}</td>
                    <td width="6%">{$value2->getStreckenplatz()}</td>
                    <td width="6%">{$value2->getAkPlatz()}</td>
                    <td width="5%">{$value2->getStartnummer()}</td>
                    <td width="20%">{$value2->getGesamtname()}</td>
                    <td width="10%">{$value2->getAltersklasse()->getAltersklasseKurz()} {$value2->getGeschlecht()}</td>
                    <td width="20%">{$value2->getVerein()->getVerein()}</td>
                    <td width="6%">{$value2->getStrecke()->getBezKurz()}</td>
                        <td width="6%">{$value2->getStreckenart()}</td>
                        <td width="5%">{$value2->getMeter()}</td>
                        <td width="5%">{$value2->getGeld()}</td>
                </tr>
             {assign var="i" value=$i+1}
       {/foreach}									
    </tbody>
</table>
<br/><br/>
{/foreach}	