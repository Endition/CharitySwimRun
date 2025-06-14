		<div class="card">
			<h5 class="card-header">Unternehmen fusionieren</h5>
			<div class="card-body">
				<div class="row">
					<div class="col-lg-12">
						<form role="form" name="UnternehmensfusionsForm" id="UnternehmensfusionsForm" action="{$actionurl}"
							method="POST" class="form-horizontal">
							<div class="form-group">
								<label class="form-label">Zielverein</label>
									<select name="zielverein" class="form-control" required>
										{foreach from=$vereine key=schluessel item=value}
											<option value="{$value->getId()}">
												{$value->getUnternehmen()} (Id: {$value->getId()})
											</option>
										{/foreach}
									</select>
							</div>
							<div class="form-group">
								<label class="form-label">Unternehmen das entfernt wird</label>
									<select name="ausgangsverein" class="form-control" required>
										{foreach from=$vereine key=schluessel item=value}
											<option value="{$value->getId()}">
												{$value->getUnternehmen()} (Id: {$value->getId()})
											</option>
										{/foreach}
									</select>
							</div>
							<div class="form-group">
								<label class="form-label"></label>
									<button type="submit" name="sendUnternehmensfusionsData"
										class="btn btn-primary">Fusionieren</button>
									<button type="reset" class="btn btn-warning">Reset</button>
							</div>

						</form>
					</div>
				</div>
			</div>
		</div>