		<div class="card">
			<h5 class="card-header">Fehlbuchungen zuordnen</h5>
			<div class="card-body">
				{if is_array($fehlbuchungen)  && is_array($fehlbuchungen) > 0}
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
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td>
								<select class="form-control" name="tpactionselect" required>
										<option>auswählen</option>
										<option value="zuordnen">zuordnen</option>
										<option value="delete">löschen</option>
									</select>
								</td>
								<td><button type="submit" name="sendFehlbuchungenData" value="" class="btn btn-primary" formaction="{$actionurl}" >ausführen</button></td>
								<td><input type="submit" value="test" name="test" /></td>
							</tr>
						</tbody>
				</table>
				</form>
				{/if}
			</div>
		</div>