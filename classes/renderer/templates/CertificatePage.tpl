<div class="card">
	<h5 class="card-header">Einzelurkunde drucken</h5>
	<div class="card-body">
		<div class="row">
			<div class="col-lg-12">
				<form role="form" name="TeilnehmerSuchenForm" id="TeilnehmerSuchenForm"
					action="{$pdfurl}&action=drucken" method="POST" class="form-horizontal">
					<div class="input-group mb-3">
						<input type="hidden" class="form-control" value="" name="id" id="id">
						<input type="hidden" class="form-control" value="" name="sendTeilnehmerDruckenData" id="sendTeilnehmerDruckenData">
						<input type="text" class="form-control" value="" name="TeilnehmerSuchen" id="TeilnehmerSuchen"
							placeholder="Teilnehmer suchen" aria-label="Teilnehmer suchen"
							aria-describedby="basic-addon2">
						                    <script type="text/javascript">
											$(document).ready(function($){ 
											   $("#TeilnehmerSuchen").autocomplete({
												   source: function(request, response) {
													   $.ajax({
														   url: "api/teilnehmer/search/"+$("#TeilnehmerSuchen").val(),
														   dataType: "json",
														   success: function(data) {
															   response(data);
														   }
													   });
												   },
												   minLength: 1,
												   select: function(event, ui) {
														$("#TeilnehmerSuchen").val(ui.item.label);
														$("#id").val(ui.item.value);
														return false;
													}
												   });
											 });
										</script>
						<div class="input-group-append">
							<button class="btn btn-primary" type="submit">Drucken</button>
						</div>
					</div>

				</form>
			</div>
		</div>
	</div>
</div>

{include file="ContentListPrinting.tpl"}