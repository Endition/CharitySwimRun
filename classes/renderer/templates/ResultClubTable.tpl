<table class="table table-striped table-bordered table-hover" data-toggle="table"
  data-search="true"id="dataTables-ergebnisse">
    <thead>
        <tr value="runden" id="erfassungsart">
            <th>Platz</th>
            <th>Name</th>
            <th>Mitglieder</th>
            <th>{$konfiguration->getStreckenart() }</th>
            <th>Meter</th>
            <th>Geld</th>					 				
        </tr>
    </thead>
    <tbody>
    {assign var="i" value=1}
        {foreach from=$ergebnisse key=schluessel item=value}
            <tr>
                <td>{$i++}</td>
                <td>{$value->getVerein()}</td>
                <td>{$value->getMitgliederList()->count()}</td>
                <td>{$value->getGesamtStreckenart()}</td>
                <td>{$value->getGesamtMeter()}</td>
                <td>{$value->getGesamtGeld()}</td>
            </tr>
        {/foreach}									
    </tbody>
</table>
