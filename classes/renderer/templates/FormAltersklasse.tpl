<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<h5 class="card-header">Sonderfunktionen</h5>
			<div class="card-body">
				<div class="row">
					<div class="col-lg-12">
						<form role="form" name="AltersklassenZuordnungForm" id="AltersklassenZuordnungForm"
							action="{$actionurl}&action=sonderfunktionen" method="POST" class="form-horizontal">
							<div class="form-group">
								<div class="col-sm-12">
								<button type="submit" name="sendZuodnungData" 
										 class="btn btn-primary">Zuordnung prüfen</button>
									{if $jahrgang == true}
									<button type="submit" name="sendDLO2017Data"
										 class="btn btn-primary">AKs gem.  DLO 2017 anlegen</button>
									<button type="submit" name="sendPCSData"
										 class="btn btn-primary">AKs gem.  PCS anlegen</button>
									{/if}
									<button type="submit" name="sendBerechneZuordnungNeu"
										 class="btn btn-info">AK Zuordnung neuberechnen</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>




<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<h5 class="card-header">Altersklasse anlegen</h5>
			<div class="card-body">
				<div class="row">
					<div class="col-lg-12">
						<form role="form" name="AltersklassenForm" id="AltersklassenForm"
							action="{$actionurl}" method="POST" class="form-horizontal">
							{include file='templateInputElement.tpl' name='id' type='hidden' value=$altersklasse->getId() required=true}
                            {include file='templateInputElement.tpl' name='altersklasse' type='text' value=$altersklasse->getAltersklasse() required=true bezeichnung='Altersklasse'}
                            {include file='templateInputElement.tpl' name='altersklasseKurz' type='text' value=$altersklasse->getAltersklasseKurz() required=true bezeichnung='Abk. der AK' maxlength=6}

							{if $jahrgang == true}
								{include file='templateInputElement.tpl' name='uDatum' type='number' value=$uDatum required=true bezeichnung='Untere Jahresgrenze (der niedrige Wert)' minlength=4 maxlength=4 min=1910 max=2050}
								{include file='templateInputElement.tpl' name='oDatum' type='number' value=$oDatum required=true bezeichnung='Obere Jahresgrenze (der hohe Wert)' minlength=4 maxlength=4 min=1910 max=2050}
							{else}
								{include file='templateInputElement.tpl' name='StartAlter' type='number' value=$StartAlter required=true bezeichnung='von Alter' minlength=1 maxlength=3 min=0 max=999}
								{include file='templateInputElement.tpl' name='EndeAlter' type='number' value=$EndeAlter required=true bezeichnung='bis Alter' minlength=1 maxlength=3 min=0 max=999 datamin="StartAlter"}
							{/if}
							{include file='templateInputElement.tpl' name='startgeld' type='number' value=$altersklasse->getStartgeld() required=true bezeichnung='Startgeld'}

							{if $konfiguration->getTransponder() eq true}	
								{include file='templateInputElement.tpl' name='tpgeld' type='number' value=$altersklasse->getTpgeld() required=true bezeichnung='Transpondergeld'}
							{/if}
							{include file='templateInputElement.tpl' name='wertungsschluessel' type='number' value=$altersklasse->getWertungsschluessel() required=false bezeichnung='Wertungsschluessel'}
							{include file='templateInputElement.tpl' name='rekord' type='number' value=$altersklasse->getRekord() required=false bezeichnung='Rekord'}
							{include file='templateInputElement.tpl' name='urkunde' type='number' value=$altersklasse->getUrkunde() required=false bezeichnung='Urkunde'}
							{include file='templateInputElement.tpl' name='bronze' type='number' value=$altersklasse->getBronze() required=false bezeichnung='Bronze'}
							{include file='templateInputElement.tpl' name='silber' type='number' value=$altersklasse->getSilber() required=false bezeichnung='Silber'}
							{include file='templateInputElement.tpl' name='gold' type='number' value=$altersklasse->getGold() required=false bezeichnung='Bronze'}
							{include file='templateInputElement.tpl' name='sendAltersklasseData' type='submit'}

						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<h5 class="card-header">Erläuterungen</h5>
			<div class="card-body">
			            <ul>
			                 <li>Urkunde/Bronze/Silber/Gold: Hier hat man die Möglichkeit Grenzwerte einzugeben ab denen die Starter eine Urkunde, oder eine Medaille bekommen.
			            </ul>
			</div>
		</div>
	</div>
</div>