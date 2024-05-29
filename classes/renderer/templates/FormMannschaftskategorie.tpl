<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<h5 class="card-header">Mannschaftskategorie anlegen</h5>
			<div class="card-body">
						<form role="form" name="MannschaftskategorieForm" id="StreckenForm"
							action="{$actionurl}" method="POST" class="form-horizontal">
							{include file='templateInputElement.tpl' name='id' type='hidden' value=$mannschaftskategorie->getId() required=true}
                            {include file='templateInputElement.tpl' name='mannschaftskategorie' type='text' value=$mannschaftskategorie->getMannschaftskategorie() required=true min=6 bezeichnung='Bezeichnung der Kategorie'}
                            {include file='templateInputElement.tpl' name='sendMannschaftskategorieData' type='submit'}
						</form>
			</div>
		</div>
	</div>
</div>
