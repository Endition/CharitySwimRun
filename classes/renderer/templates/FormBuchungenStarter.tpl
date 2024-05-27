
<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<h5 class="card-header">Buchungen eines Teilnehmers</h5>
			<div class="card-body" style="height: 400px; overflow-y: scroll;">
				<table width="100%"
					class="table table-striped table-bordered table-hover"
					id="dataTables-example">
					<thead>
						<tr>
							<th>Id</th>
							{if $konfiguration->getTransponder() eq true}
							<th>TP</th>
							{/if}
							<th>Zeit</th>
							<th>Rundenzeit</th>
							<th>Gesamtzeit</th>
							<th>löschen</th>
						</tr>
					</thead>
					<form role="form" name="FehlbuchungenForm" id="FehlbuchungenForm"
							action="{$actionurl}" method="POST">
						<tbody>
							<tr>
								<td></td>
								{if $konfiguration->getTransponder() eq true}
								<td></td>
								{/if}
								<td></td>
								<td></td>
								<td><button type="submit" value="{$teilnehmerId}" formaction="{$actionurl}&action=add&amp;teilnehmerid={$teilnehmerId}" name="sendImpulseEinlaufenData" class="btn btn-primary">Buchung <i class="fa-solid fa-pen-to-square"></i></button></td>
								<td></td>
							</tr>
							{if $impulse->count() > 0}
								{foreach from=$impulse key=schluessel item=value}
								<tr>
									<td>{$value->getId()}</td>
									{if $konfiguration->getTransponder() eq true}
									<td>{$value->getTransponderId()}</td>
									{/if}
									<td>{$value->getTimestamp("d.m.Y H:i:s")}</td>
									<td>{$value->getRundenzeit("H:i:s")}</td>
									<td>{$value->getGesamtzeit("H:i:s")}</td>
									<td><a title="entfernen"
									href='{$actionurl}&action=delete&amp;impulsid={$value->getId()}&amp;teilnehmerid={$value->getTeilnehmer()->getId()}'><i class="fa-solid fa-trash"></i></a></td>
								</tr>
								{/foreach}
							{else}
								<tr>
									<td colspan="6">Noch keine Buchungen vorhanden</td>
								</tr>
							{/if}
							<tr>
								<td></td>
								{if $konfiguration->getTransponder() eq true}
								<td></td>
								{/if}
								<td></td>
								<td></td>
								<td><button type="submit" value="{$teilnehmerId}" formaction="{$actionurl}&action=add&teilnehmerid={$teilnehmerId}" name="sendImpulseEinlaufenData" class="btn btn-primary">Buchung <i class="fa-solid fa-plus"></i></button></td>
								<td><button type="submit" value="{$teilnehmerId}" formaction="{$actionurl}&action=deleteall&teilnehmerid={$teilnehmerId}" name="sendImpulseEinlaufenData" class="btn btn-danger">Alle Buchungen <i class="fa-solid fa-trash"></i></button></td>
							</tr>
						</tbody>
					</form>
				</table>
			</div>
		</div>
	</div>
</div>