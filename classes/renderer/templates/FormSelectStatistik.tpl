
		<div class="card">
			<h5 class="card-header">Statistik auswählen</h5>
			<div class="card-body">
						<form role="form" name="SelectStatstikForm" id="SelectStatstikForm"
							method="POST" class="form-horizontal">
							<div class="form-group">
								<label class="form-label">Statistik</label>
												    <select 
												    	name="statistikauswahl"
												    	class="form-control" title="Bitte auswählen..."> 
															<option value="TNpLeser">Momentane Teilnehmer pro Leser</option>
															<option value="BpH">{$konfiguration->getStreckenart()} pro Stunde</option>
															<option value="BpL">{$konfiguration->getStreckenart()} pro Leser</option>
															<option value="BpHuLeser">{$konfiguration->getStreckenart()} pro Stunde und Leser</option>
															<option value="Performance">aktuelle Performance</option>
															<option value="SpH">Aktive Teilnehmer pro Stunde</option>
															<option value="TNpH">Gestartete Teilnehmer pro Stunde</option>
    												</select>								
							</div>
							<div class="form-group">
								<label class="form-label"></label>
								<div class="col-sm-8">
							<button type="submit" name="sendStatstikViewData" formaction="{$actionurl}"
								class="btn btn-primary">Anzeigen</button>
							</div></div>
						</form>

			</div>
		</div>
