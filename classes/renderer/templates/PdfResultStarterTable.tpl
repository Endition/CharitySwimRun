
<table class="table table-striped table-bordered" cellspacing="0" width="100%" id="dataTables-ergebnisse">
<thead>
            <tr>
                <th><b>Platz</b></th>
                <th><b>St.Platz</b></th>
                <th><b>AKPlatz</b></th>
                <th><b>StNr</b></th>
                <th><b>Name</b></th>
                <th><b>Gesch.</b></th>
                <th><b>AK</b></th>
                <th><b>Verein</b></th>
                <th><b>Strecke</b></th>
                <th><b>{$konfiguration->getStreckenart() }</b></th>
                <th><b>Meter</b></th>
                <th><b>Geld</b></th>					 				

            </tr>
        </thead>
        <tbody>
            {foreach from=$ergebnisse key=schluessel item=value}
                <tr>
                    <td>{if $specialEvaluation == null}  {$value->getGesamtplatz()} {else} {/if}</td>
                    <td>{if $specialEvaluation == null}  {$value->getStreckenplatz()} {else} {/if}</td>
                    <td>{if $specialEvaluation ==null}  {$value->getAkPlatz()} {else} {/if}</td>
                    <td>{$value->getStartnummer()}</td>
                    <td>{$value->getGesamtname()}</td>
                    <td>{$value->getGeschlecht()}</td>
                    <td>{$value->getAltersklasse()->getAltersklasseKurz()} {$value->getGeschlecht()}</td>
                    <td>{$value->getVerein()->getVerein()}</td>
                    <td>{$value->getStrecke()->getBezKurz()}</td>
                    <td>{if $specialEvaluation == null}  {$value->getStreckenart()} {else} {$value->getStreckenartSonderwertung()}  {/if}</td>
                    <td>{if $specialEvaluation == null}  {$value->getMeter()} {else} {$value->getMeterSonderwertung()}  {/if}</td>
                    <td>{if $specialEvaluation == null}  {$value->getGeld()}  {else} {$value->getGeldSonderwertung()} {/if}</td>			 				
                </tr>
            {/foreach}								
        </tbody>
    </table> 
<