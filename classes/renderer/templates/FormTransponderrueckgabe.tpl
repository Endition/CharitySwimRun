		<div class="card">
			<h5 class="card-header">Transponder zurück nehmen</h5>
			<div class="card-body">
						<form role="form" name="TransponderRueckgabeForm" id=""TransponderRueckgabeForm"
							action="{$actionurl}" method="POST" class="form-horizontal">
							<div class="container">
							<div class="row">
								{foreach from=$teilnehmer key=schluessel item=value}
								<div class="d-grid gap-2 col-2 mx-auto">	
									<button type="submit" name="sendTransponderRueckgabeData" value="{$value->getId()}" class="btn btn-primary">TP: {$value->getTransponder()}<br> ({$value->getGesamtname()})</button>						
								</div>								
								{/foreach}	
								</div>		
								</div>						
						</form>
			</div>
		</div>