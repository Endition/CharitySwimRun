
        <div class="card">
            <h5 class="card-header">Verein anlegen/bearbeiten</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form role="form" name="VereineForm" id="VereineForm"
                              action="{$actionurl}" method="POST" class="form-horizontal">

                              {include file='templateInputElement.tpl' name='id' type='hidden' value=$verein->getId() required=true}
                              {include file='templateInputElement.tpl' name='verein' type='text' value=$verein->getVerein() required=true bezeichnung='Name des Vereins'}
                              {include file='templateInputElement.tpl' name='sendVereinData' type='submit'}

                            {if  $verein->getMitgliederList()->Count() > 0}
                                <div class="form-group">
                                    <label class="form-label">Teilnehnde des Vereins</label>
                                        <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="dataTables-example">
                                            <tr>
                                                <th>StNr</th>
                                                <th>TP</th>
                                                <th>Name</th>
                                            </tr>
                                            {foreach from=$verein->getMitgliederList() key=schluessel item=value}
                                                <tr>
                                                    <td>{$value->getStartnummer()}</td>
                                                    <td>{$value->getTransponder()}</td>
                                                    <td><a href="{$editTeilnehmerUrl}&action=search&id={$value->getId()}">{$value->getGesamtname()}</a></td>
                                                </tr>
                                            {/foreach}
                                        </table>
                                </div>	
                            {/if}						
                        </form>
                    </div>
                </div>
            </div>
        </div>


