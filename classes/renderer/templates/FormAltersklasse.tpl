<div class="row">
	<div class="col-8">
		<div class="card">
			<h5 class="card-header">Altersklasse anlegen</h5>
			<div class="card-body">
						<form role="form" name="AltersklassenForm" id="AltersklassenForm"
							action="{$actionurl}" method="POST" class="form-horizontal">
							{include file='templateInputElement.tpl' name='id' type='hidden' value=$ageGroup->getId() required=true}
                            {include file='templateInputElement.tpl' name='altersklasse' type='text' value=$ageGroup->getAltersklasse() required=true bezeichnung='Altersklasse'}
                            {include file='templateInputElement.tpl' name='altersklasseKurz' type='text' value=$ageGroup->getAltersklasseKurz() required=true bezeichnung='Abk. der AK' maxlength=6}

							{if $konfiguration->getAltersklassen() eq false }
								{include file='templateInputElement.tpl' name='uDatum' type='number' value=($ageGroup->getUDatum() !== null ? $ageGroup->getUDatum()->format('Y') : "") required=true bezeichnung='Untere Jahresgrenze (der niedrige Wert)' minlength=4 maxlength=4 min=1910 max=2050}
								{include file='templateInputElement.tpl' name='oDatum' type='number' value=($ageGroup->getODatum() !== null ? $ageGroup->getODatum()->format('Y') : "")  required=true bezeichnung='Obere Jahresgrenze (der hohe Wert)' minlength=4 maxlength=4 min=1910 max=2050}
							{else}
								{include file='templateInputElement.tpl' name='StartAlter' type='number' value=$ageGroup->getStartAlter() required=true bezeichnung='von Alter' minlength=1 maxlength=3 min=0 max=999 description="Das Startalter in Jahren"}
								{include file='templateInputElement.tpl' name='EndeAlter' type='number' value=$ageGroup->getEndeAlter() required=true bezeichnung='bis Alter' minlength=1 maxlength=3 min=0 max=999 description="Das Endalter in Jahren" datamin="StartAlter"}
							{/if}
							{include file='templateInputElement.tpl' name='startgeld' type='number' value=$ageGroup->getStartgeld() required=true bezeichnung='Startgeld'}

							{if $konfiguration->getTransponder() eq true}	
								{include file='templateInputElement.tpl' name='tpgeld' type='number' value=$ageGroup->getTpgeld() required=true bezeichnung='Transpondergeld'}
							{/if}
							{include file='templateInputElement.tpl' name='wertungsschluessel' type='number' value=$ageGroup->getWertungsschluessel() required=false bezeichnung='Wertungsschluessel für die Berechnung Mannschaftswertung mit Formel'}
							{include file='templateInputElement.tpl' name='rekord' type='number' value=$ageGroup->getRekord() required=false bezeichnung='Rekord in Meter in dieser Altersklasse'}
							{include file='templateInputElement.tpl' name='urkunde' type='number' value=$ageGroup->getUrkunde() required=false bezeichnung='min. Strecke in Meter für Urkunde'}
							{include file='templateInputElement.tpl' name='bronze' type='number' value=$ageGroup->getBronze() required=false bezeichnung='min. Strecke in Meter für Bronzemedaille'}
							{include file='templateInputElement.tpl' name='silber' type='number' value=$ageGroup->getSilber() required=false bezeichnung='min. Strecke in Meter für Silbermedaille'}
							{include file='templateInputElement.tpl' name='gold' type='number' value=$ageGroup->getGold() required=false bezeichnung='min. Strecke in Meter für Goldmedaille'}
							{include file='templateInputElement.tpl' name='sendAltersklasseData' type='submit'}

						</form>
			</div>
		</div>
	</div>
	<div class="col-4">
	<div class="card">
		<h5 class="card-header">Zuordnung Jahre - Altersklassen</h5>
		<div class="card-body">
			<div class="row">
				<div class="col-6">
				{assign var=i 0}
				{foreach from=$yearAgeGroupList key=year item=ageGroupNameList}
					{if $i==50}
						</div><div class="col-6">
					{/if}
					{$year}: 
					{foreach from=$ageGroupNameList item=ageGroupName}
					<span class="badge bg-{if count($ageGroupNameList) == 1 }success{else}danger{/if}">{$ageGroupName}</span>
					{/foreach}
					<br>
					{assign var=i value=$i+1}
				{/foreach}
				</div>
			</div>
		</div>
	</div>
</div>
