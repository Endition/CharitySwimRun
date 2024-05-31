<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <h5 class="card-header">Teilnehmer suchen</h5>
            <div class="card-body">
                        <form role="form" name="TeilnehmerSuchenForm" id="TeilnehmerSuchenForm"
                              action="{$actionurl}&action=search" method="POST" class="form-horizontal">
                            <div class="row">
                                <div class="col-sm-4">
                                    <input type="hidden" class="form-control" value="" name="id"  id="id">
                                    <input class="form-control" value="" name="TeilnehmerSuchen"
                                           placeholder="Teilnehmer suchen" id="TeilnehmerSuchen"
                                           required
                                           type="text"
                                           >
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
                                </div>
                                <div class="col-sm-4">
                                    <button type="submit" name="sendTeilnehmerSuchenData"
                                            class="btn btn-primary">Anzeigen</button>
                                </div>
                            </div>
                        </form>
            </div>
        </div>
    </div>
</div>
