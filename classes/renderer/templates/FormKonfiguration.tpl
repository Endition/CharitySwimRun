<div class="card">
	<h5 class="card-header">Einstellungen</h5>
	<div class="card-body">
		<div class="row">
			<div class="col-lg-12">
				<form role="form" name="KonfigurationForm" id="KonfigurationForm" action="{$actionurl}" method="POST">

				{foreach from=$settings key=schluessel item=value}
						<div class="form-group">
							<label>{$value['name']}</label>

						{if $value['type'] !== "select"}
								<input class="form-control" value="{$value['savedvalue']}" name="{$schluessel}" {if {isset($value['step'])}} step="{$value['step']}" {/if}
									{if {$value['pflichtfeld']}==true} required {/if}  type="{$value['type']}" />

						{elseif {$value['type']} == "select"}
							<select name="{$schluessel}" class="form-control" {if {$value['pflichtfeld']}==true} required
								{/if}>

								{foreach key=schluessel2 item=wert2 from=$value['value']}
									<option value="{$schluessel2}" {if {$value['savedvalue']}=={$schluessel2}}
										selected="selected" {/if}>
										{$wert2}

									</option>

								{/foreach}
							</select>

						{/if}
						<div class="form-text" id="basic-addon4">{$value['erklaerung']}</div>
					</div>
			

					{/foreach}
					<button type="submit" class="btn btn-primary" name="sendKonfiguration" formaction="{$actionurl}"> Speichern  </button>
					<button type="reset" class="btn btn-warning">Reset</button>
				</form>
			</div>
		</div>
	</div>
</div>