	<div class="col-lg-12">
		<form action="index.php?doc=buchungsuebersicht" method="post">
		</form>
	</div>
<table 	class="table table-striped table-bordered table-hover"
data-toggle="table"
data-search="true" 
data-pagination="true"
id="buchungsuebersicht">
	<thead>
		<tr>
			<th>Id</th>
			{if $konfiguration->getTransponder() eq true}
				<th>TP</th>
			{/if}
			<th>Zeit</th>
			<th>StNr</th>
			<th>löschen</th>
		</tr>
	</thead>
	<form role="form" name="FehlbuchungenForm" id="FehlbuchungenForm" action="{$actionurl}" method="POST">
		<tbody>
			{foreach from=$buchungen key=schluessel item=value}
				<tr class="odd gradeX">
					<td>{$value->getId()}</td>
					{if $konfiguration->getTransponder() eq true}
						<td>{$value->getTransponderId()}</td>
					{/if}
					<td>{$value->getTimestamp("d.m.Y H:i:s")}</td>
					<td>{$value->getTeilnehmer()->getId()}</td>
					<td><a title="entfernen"
							href='{$actionurl}&action=delete&amp;impulsid={$value->getId()}&amp;teilnehmerid={$value->getTeilnehmer()->getId()}'><i
								class="fa-solid fa-trash"></i></a></td>
				</tr>
			{/foreach}
		</tbody>
	</form>
</table>