		<div class="card">
			<h5 class="card-header">Starter importieren</h5>
			<div class="card-body">
				<div class="row">
					<div class="col-lg-12">
						<form role="form" name="ImportForm" id="ImportForm"
							action="{$actionurl}" method="POST" class="form-horizontal" enctype="multipart/form-data">
							<div class="form-group col-sm-12">
								<label class="form-label">1. Zeile ignorieren</label>
								<div class="col-sm-8">
									<input type="checkbox" name="ignorieren" value="ja" /> Ja
								</div>
							</div>
							<div class="form-group col-sm-12">
								<label class="form-label">Trennzeichen</label>
								<div class="col-sm-8">
									<input type="radio" name="trennzeichen" value=";" checked="checked" /> ; 
                               	 	<input type="radio" name="trennzeichen" value="," /> ,
                               		 <input type="radio" name="trennzeichen" value="-" /> -
								</div>
							</div>
							<div class="form-group col-sm-12">
								<label class="form-label">Datei</label>
								<div class="col-sm-8">
									<input class="form-element" type="file" name="datei" />
								</div>
							</div>
							<div class="form-group col-sm-12">
								<label class="form-label"></label>
								<div class="col-sm-8">
									<button type="submit" name="sendImportData" class="btn btn-primary">importieren</button>
								</div>
							</div>	
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<h5 class="card-header">Hinweise</h5>
			<div class="card-body">
				<div class="row">
					<div class="col-lg-12">
 								 <h4>Hinweise zur Importdatei</h4>
		                        <ul>
		                            <li><a href="assets/meldeliste.xlsx">Excel Datei</a> in genau der Reihenfolge benutzen wie vorgegeben. Rechts daneben könne Spalten hinzugefügt werden, die aber beim Import ignoriert werden</li>
		                            <li>Buchstaben für das Geschlecht (M/W) müssen groß geschrieben sein</li>
		                            <li>Schreibweisen bei den Vereinen müssen exakt gleich sein</li>
		                            <li>Bei Strecke muss die Nummer der Strecke eingegeben werden, die das Programm vergeben hat</li>
		                            <li>AKS werden vom Skript berechnet</li>
		                        </ul>
		                        <h4>Hinweise zum Import</h4>
			                        <ul>
			                            <li>Excel -> Datei -> Speichern unter -> .csv Trennzeichen getrennt</li>
			                            <li>Hier im Skript die .csv auswählen</li>
			                            <li>"1. Zeile ignorieren" -> wenn in der ersten Zeile der .csv die überschriften der Tabelle stehen</li>
			                            <li>"jetzt importieren" klicken</li>
			                            <li>auf Fehlermeldungen achten. Daten in der Datenbank überprüfen</li>
			                            <li>Vor erneutem Import Datenbank leeren </li>
			                        </ul>	
				</div></div>
			</div>
		</div> 