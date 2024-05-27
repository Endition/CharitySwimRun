<table 	
class="table table-striped table-bordered table-hover"
data-toggle="table"
  data-search="true">
{assign var="i" value="1"}
						<tr>
							<th align="center"  width="30"><b>Nr.</b></th>
							<th align="center" ><b>Zeit</b></th>
							<th align="center" ><b>Rundezeit</b></th>
							<th align="center" ><b>Gesamtzeit</b></th>
						</tr>
							{if count($impulse) > 0}
								{foreach from=$impulse key=schluessel item=value}
								<tr>
									<td align="center" >{$i++}</td>
									<td align="right" >{$value->getTimestamp("d.m.Y H:i:s")}</td>
									<td align="right" >{$value->getRundenzeit("H:i:s")}</td>
									<td align="right" >{$value->getGesamtzeit("H:i:s")}</td>
								</tr>
								{/foreach}
							{else}
								<tr>
									<td colspan="6">Noch keine Buchungen vorhanden</td>
								</tr>
							{/if}
				</table>
