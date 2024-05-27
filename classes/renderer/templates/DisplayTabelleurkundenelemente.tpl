		<div class="card">
			<h5 class="card-header">angelegte Urkundenelemente</h5>
			<div class="card-body">
				<table 					class="table table-striped table-bordered table-hover"
				data-toggle="table"
				  data-search="true">
					<thead>
						<tr>
							<th>Id</th>
							<th>X-Wert</th>
							<th>Y-Wert</th>
							<th>Breite</th>
							<th>Höhe</th>
							<th>Inhalt</th>
							<th>Freitext</th>
							<th>Schriftart</th>
							<th>Schriftgrösse</th>
							<th>Schrifttyp</th>
							<th>Ausrichtung</th>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						{foreach from=$urkundenelemente key=schluessel item=value}
						<tr>
							<td>{$value->getId()}</td>
							<td>{$value->getX_wert()}</td>
							<td>{$value->getY_wert()}</td>
							<td>{$value->getBreite()}</td>
							<td>{$value->getHoehe()}</td>
							<td>{$value->getInhalt()}</td>
							<td>{$value->getFreitext()}</td>
							<td>{$value->getSchriftart()}</td>
							<td>{$value->getSchriftgroesse()}</td>
							<td>{$value->getSchrifttyp()}</td>
							<td>{$value->getAusrichtung()}</td>
							<td><a title="beabeiten"
								href='{$link}&action=edit&amp;id={$value->getId()}'><i class="fa-solid fa-pen-to-square"></i></a></td>
							<td><a title="entfernen"
								href='{$link}&action=delete&amp;id={$value->getId()}'><i class="fa-solid fa-trash"></i></a></td>
						</tr>
						{/foreach}
					</tbody>
				</table>
			</div>
		</div>