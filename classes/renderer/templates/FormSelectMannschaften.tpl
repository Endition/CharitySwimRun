<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<h5 class="card-header">Mannschaft auswählen</h5>
			<div class="card-body">
						<form role="form" name="VereinsfusionsForm" id="VereinsfusionsForm"
							method="POST" class="form-horizontal">
							<div class="form-group">
								<label class="form-label">Mannschaft</label>
								<div class="col-sm-8">
												    <select 
												    	name="mannschaftsid"
												    	class="form-control"  title="Bitte auswählen..."> 
														{foreach from=$mannschaften item=value}
															<option value="{$value->getId()}">
																{$value->getMannschaft()}
															</option>
  														{/foreach}
    												</select>									
								</div>
							</div>
														<div class="form-group">
								<label class="form-label"></label>
								<div class="col-sm-8">
															<button type="submit" name="sendMannschaftViewData" formaction="{$actionurl}&action=edit"
								class="btn btn-primary">Anzeigen</button>
							<button type="submit" name="sendMannschaftViewData" formaction="{$actionurl}&action=delete"
								class="btn btn-danger">Löschen</button>
																</div>
							</div>

						</form>
			</div>
		</div>
	</div>
</div>