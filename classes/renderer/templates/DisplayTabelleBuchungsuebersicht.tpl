	<div class="col-lg-12">
		<form action="index.php?doc=buchungsuebersicht" method="post">
		</form>
	</div>
<table 	class="table table-striped table-bordered table-hover"
data-toggle="table"
data-search="true" 
data-filter-control="true"
data-sortable="true"
data-show-search-clear-button="true"
data-pagination="true"
id="buchungsuebersicht">
	<thead>
		<tr>
			<th>Id</th>
			{if $konfiguration->getTransponder() eq true}
				<th>TP</th>
			{/if}
			<th data-field="Zeit" data-sortable="true">Zeit</th>
			<th data-field="Name" data-filter-control="select">Name</th>
			<th data-field="StNr" data-filter-control="select">StNr</th>
			<th>löschen</th>
		</tr>
	</thead>
	<form role="form" name="FehlbuchungenForm" id="FehlbuchungenForm" action="{$actionurl}" method="POST">
		<tbody>
			{foreach from=$hitList key=schluessel item=hit}
				<tr class="odd gradeX">
					<td>{$hit->getId()}</td>
					{if $konfiguration->getTransponder() eq true}
						<td>{$hit->getTransponderId()}</td>
					{/if}
					<td>{$hit->getTimestamp("d.m.Y H:i:s")}</td>
					<td>{$hit->getTeilnehmer()->getGesamtname()}</td>
					<td>{$hit->getTeilnehmer()->getStartnummer()}</td>
					<td><a title="entfernen"
							href='{$actionurl}&action=delete&amp;impulsid={$hit->getId()}&amp;teilnehmerid={$hit->getTeilnehmer()->getId()}'><i
								class="fa-solid fa-trash"></i></a></td>
				</tr>
			{/foreach}
		</tbody>
	</form>
</table>