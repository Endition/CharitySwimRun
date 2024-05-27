<table 					
class="table table-striped table-bordered table-hover"
data-toggle="table"
data-search="true" 
id="dataTables-meldelisten">
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
    {if $ausgabetyp == "HTML"}
        <tfoot style="display: table-header-group;">
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </tfoot>
{/if}
<tbody>
    {foreach from=$meldelisten key=schluessel item=value}
        <tr>
            <td>{$value->getStartnummer()}</td>
            <td>{$value->getTransponder()}</td>
            <td>{$value->getGesamtname()}</td>
            <td>{$value->getGeburtsdatum()->format('Y')}</td>
            <td>{$value->getGeschlecht()}</td>
            <td>{$value->getAltersklasse()->getAltersklasseKurz()} {$value->getGeschlecht()}</td>
            <td>{$value->getVerein()->getVerein()}</td>
            <td>{$value->getStrecke()->getBezKurz()}</td>
            <td>{$value->getAltersklasse()->getStartgeld()} €</td>
            <td>{$value->getAltersklasse()->getTpgeld()} €</td>
        </tr>
    {/foreach}								
</tbody>
</table> 