<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<h5 class="card-header">angelegte Altersklassen</h5>
			<div class="card-body">
				<table
					class="table table-striped table-bordered table-hover"
					data-toggle="table"
  					data-search="true"
					id="tableAltersklasse"
					data-pagination="true">
					<thead>
						<tr>
							<th>Id</th>
							<th>Altersklasse</th>
							<th></th>
							{if $jahrgang == 0}
							<th>untere Grenze</th>
							<th>obere Grenze</th>
							{else}
							<th>von Alter</th>
							<th>bis Alter</th>
							{/if}
							<th>Startgeld</th>
							{if $konfiguration->getTransponder() eq true}	
							<th>TP-Pfand</th>
							{/if}
							<th>Wertungsschlüssel</th>
							<th>Rekord</th>
							<th>Urkunde</th>
							<th>Bronze</th>
							<th>Silber</th>
							<th>Gold</th>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						{foreach from=$altersklassen key=schluessel item=value}
						<tr class="odd gradeX">
							<td>{$value->getId()}</td>
							<td>{$value->getAltersklasse()}</td>
							<td>{$value->getAltersklasseKurz()}</td>
							{if $jahrgang == 0}
							<td>{$value->getUDatum()->format('Y')}</td>
							<td>{$value->getODatum()->format('Y')}</td>
							{else}
							<td>{$value->getStartAlter()}</td>
							<td>{$value->getEndeAlter()}</td>
							{/if}
							<td>{$value->getStartgeld()}</td>
							{if $konfiguration->getTransponder() eq true}	
								<td>{$value->getTpgeld()}</td>
							{/if}
							<td>{$value->getWertungsschluessel()}</td>
							<td>{$value->getRekord()}</td>
							<td>{$value->getUrkunde()}</td>
							<td>{$value->getBronze()}</td>
							<td>{$value->getSilber()}</td>
							<td>{$value->getGold()}</td>
							<td><a title="beabeiten"
								href='{$link}&action=edit&amp;id={$value->getId()}'><i class="fa-solid fa-pen-to-square"></i></a></td>
							<td><a title="entfernen"
								href='{$link}&action=delete&amp;id={$value->getId()}'><i class="fa-solid fa-trash"></i></a></td>
						</tr>
						{/foreach}
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>