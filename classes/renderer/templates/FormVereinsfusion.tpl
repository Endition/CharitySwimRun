		<div class="card">
			<h5 class="card-header">Vereine fusionieren</h5>
			<div class="card-body">
				<div class="row">
					<div class="col-lg-12">
						<form role="form" name="VereinsfusionsForm" id="VereinsfusionsForm" action="{$actionurl}"
							method="POST" class="form-horizontal">
							<div class="form-group">
								<label class="form-label">Zielverein</label>
									<select name="zielverein" class="form-control" required>
										{foreach from=$vereine key=schluessel item=value}
											<option value="{$value->getId()}">
												{$value->getVerein()}
											</option>
										{/foreach}
									</select>
							</div>
							<div class="form-group">
								<label class="form-label">Verein der entfernt wird</label>
									<select name="ausgangsverein" class="form-control" required>
										{foreach from=$vereine key=schluessel item=value}
											<option value="{$value->getId()}">
												{$value->getVerein()}
											</option>
										{/foreach}
									</select>
							</div>
							<div class="form-group">
								<label class="form-label"></label>
									<button type="submit" name="sendVereinsfusionsData"
										class="btn btn-primary">Fusionieren</button>
									<button type="reset" class="btn btn-warning">Reset</button>
							</div>

						</form>
					</div>
				</div>
			</div>
		</div>