<table 					class="table table-striped table-bordered table-hover"
data-toggle="table"
  data-search="true" id="dataTables-ergebnisse">
    <thead>
        <tr>
            <th></th>
            <th>Platz</th>
            <th>StNr</th>
            <th>Name</th>
            <th>Mitglieder</th>
            <th>{$konfiguration->getStreckenart()}</th>
             {if  $konfiguration->getMannschaftPunkteBerechnen() == "Formel"}
                  <th>Punkte</th>
            {else}
                  <th>Meter</th>
             {/if}
            <th>Geld</th>					 				
        </tr>
    </thead>
    <tbody>
        {assign var="i" value=1}
        {foreach from=$ergebnisse key=schluessel item=value}
            <tr>
                <td></td>
                <td>{$i++}</td>
                <td></td>
                <td>{$value->getMannschaft()}</td>
                <td>{$value->getMitgliederList()->count()}</td>
                    {if  $konfiguration->getMannschaftPunkteBerechnen()  == "Formel"}
                    <td>{$value->getPunkte()}</td>
                    {else}
                        <td>{$value->getGesamtMeter()}m</td>    
                    {/if}
                <td>{$value->getGesamtGeld()}</td>					 				

            </tr>
        {/foreach}									
    </tbody>
</table>