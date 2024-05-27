{foreach from=$meldelisten key=schluessel item=value}
    <b>{$value->getVerein()}</b> ({$value->getMitgliederAnzahl()} Mitglieder)<br/>
    <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="dataTables-ergebnisse">
    <thead>
        <tr>
            <th><b>StNr</b></th>
            <th><b>TP</b></th>
            <th><b>Name</b></th>
            <th><b>Jahrgang</b></th>
            <th><b>Geschlecht</b></th>
            <th><b>AK</b></th>
            <th><b>Verein</b></th>
            <th><b>Strecke</b></th>
            <th><b>Startgeld</b></th>
            <th><b>TP-Pfand</b></th>
        </tr>
    </thead>
    <tbody>
         {assign var="i" value=1 nocache}
       {foreach from=$value->getMitgliederList()  key=schluessel2 item=value2}
        <tr {if ($i % 2) != 0} style="background-color:#dce0e8;"{/if}>
            <td>{$value2->getStartnummer()}</td>
            <td>{$value2->getTransponder()}</td>
            <td>{$value2->getGesamtname()}</td>
            <td>{$value2->getGeburtsdatum()->format('Y')}</td>
            <td>{$value2->getGeschlecht()}</td>
            <td>{$value2->getAltersklasse()->getAltersklasseKurz()} {$value2->getGeschlecht()}</td>
            <td>{$value2->getVerein()->getVerein()}</td>
            <td>{$value2->getStrecke()->getBezKurz()}</td>
            <td>{$value2->getAltersklasse()->getStartgeld()} €</td>
            <td>{$value2->getAltersklasse()->getTpgeld()} €</td>
        </tr>
             {assign var="i" value=$i+1}
       {/foreach}									
    </tbody>
</table>
<br/><br/>
{/foreach}	