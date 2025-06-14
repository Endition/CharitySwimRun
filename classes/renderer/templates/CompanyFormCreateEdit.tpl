
        <div class="card">
            <h5 class="card-header">Unternehmen anlegen/bearbeiten</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form role="form" name="UnternehmeneForm" id="UnternehmeneForm"
                              action="{$actionurl}" method="POST" class="form-horizontal">

                              {include file='templateInputElement.tpl' name='id' type='hidden' value=$unternehmen->getId() required=true}
                              {include file='templateInputElement.tpl' name='unternehmen' type='text' value=$unternehmen->getUnternehmen() required=true bezeichnung='Name des Unternehmens'}
                              {include file='templateInputElement.tpl' name='sendUnternehmenData' type='submit'}

                            {if  $unternehmen->getMitgliederList()->Count() > 0}
                                <div class="form-group">
                                    <label class="form-label">Teilnehmende des Unternehmens</label>
                                        <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="dataTables-example">
                                            <tr>
                                                <th>StNr</th>
                                                <th>TP</th>
                                                <th>Name</th>
                                            </tr>
                                            {foreach from=$unternehmen->getMitgliederList() key=schluessel item=value}
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


