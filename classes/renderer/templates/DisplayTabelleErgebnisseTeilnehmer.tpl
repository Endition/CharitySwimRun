<script src="vendor/eligrey/filesaver/dist/FileSaver.min.js"></script>
<script src="vendor/parallax/jspdf/dist/jspdf.umd.min.js"></script>
<script src="vendor/hhurz/tableexport.jquery.plugin/tableExport.min.js"></script>
    
    <table 	class="table table-striped table-bordered table-hover"
    data-toggle="table"
      data-search="true"
      data-sortable="true"
      data-sort-name="Meter"
      data-sort-order="desc"
      data-show-export="true"
      data-export-data-type="basic"
      data-export-types="['pdf','txt','sql','csv']"
      data-filter-control="true"
      data-show-search-clear-button="true"
      data-pagination="true"
>
        <thead>
            <tr>
                <th data-field="Platz" data-sortable="true"><b>Platz</b></th>
                <th data-field="StPlatz" data-sortable="true"><b>St.Platz</b></th>
                <th data-field="AKPlatz" data-sortable="true"><b>AKPlatz</b></th>
                <th><b>StNr</b></th>
                <th><b>Name</b></th>
                <th  data-field="Geschlecht" data-filter-control="select"><b><i class="fa fa-venus-mars fa-fw"></b></th>
                <th  data-field="AK" data-filter-control="select"><b>AK</b></th>
                <th  data-field="Verein" data-filter-control="select"><b>Verein</b></th>
                <th  data-field="Strecke" data-filter-control="select"><b>Strecke</b></th>
                <th><b>{$konfiguration->getStreckenart() }</b></th>
                <th data-field="Meter" data-sortable="true"><b>Meter</b></th>
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