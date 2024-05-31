
<table class="table table-striped table-bordered" cellspacing="0" width="100%" id="dataTables-ergebnisse">
<thead>
            <tr>
                <th><b>StNr</b></th>
                <th><b>Name</b></th>
                <th><b>Gesch.</b></th>
                <th><b>AK</b></th>
                <th><b>Verein</b></th>
                <th><b>Strecke</b></th>				 				
            </tr>
        </thead>
        <tbody>
            {foreach from=$ergebnisse key=schluessel item=value}
                <tr>
                    <td>{$value->getStartnummer()}</td>
                    <td>{$value->getGesamtname()}</td>
                    <td>{$value->getGeschlecht()}</td>
                    <td>{$value->getAltersklasse()->getAltersklasseKurz()} {$value->getGeschlecht()}</td>
                    <td>{$value->getVerein()->getVerein()}</td>
                    <td>{$value->getStrecke()->getBezKurz()}</td>		 				
                </tr>
            {/foreach}								
        </tbody>
    </table> 
<