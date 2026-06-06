		<div class="card">
			<h5 class="card-header">Fehlbuchungen zuordnen</h5>
			<div class="card-body">
				{if is_array($fehlbuchungen)  && count($fehlbuchungen) > 0}
				Summe Fehlbuchungen: {count($fehlbuchungen)}
				<form role="form" name="FehlbuchungenForm" id="FehlbuchungenForm" action="{$actionurl}" method="POST">
				<table 	class="table table-striped table-bordered table-hover" data-toggle="table"data-search="true" data-pagination="true"
				>
					<thead>
						<tr>
							<th>Id</th>
							<th>Transponder</th>
							<th>Leser</th>
							<th>Zeit</th>
							<th>Startnummer</th>
							<th></th>
						</tr>
					</thead>

						<tbody>
							{foreach from=$fehlbuchungen key=schluessel item=value}
							<tr>
								<td>{$value->getId()}</td>
								<td>{$value->getTransponderId()}</td>
								<td>{$value->getLeser()}</td>
								<td>{$value->getTimestamp("d.m.Y H:i:s")}</td>
								<td><input class="form-control" type="text" name="tpzuordnenTnId[{$value->getId()}]"></td>
								<td><input class="form-check-input" type="checkbox" name="tpaction[{$value->getId()}]" value="true"></td>
							</tr>
							{/foreach}
						</tbody>
				</table>
				<div class="row mt-3">
					<div class="col-md-4 offset-md-8 d-flex justify-content-end">
						<select class="form-select me-2" name="tpactionselect" required>
							<option value="">auswählen</option>
							<option value="zuordnen">zuordnen</option>
							<option value="delete">löschen</option>
						</select>
						<button type="submit" name="sendFehlbuchungenData" value="true" class="btn btn-primary">ausführen</button>
					</div>
				</div>
				</form>
				{/if}
			</div>
		</div>