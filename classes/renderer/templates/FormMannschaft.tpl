<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <h5 class="card-header">Mannschaft anlegen</h5>
            <div class="card-body">
                        <form role="form" name="MannschaftForm" id="MannschaftForm"
                              action="{$actionurl}" method="POST" class="form-horizontal">

                              {include file='templateInputElement.tpl' name='id' type='hidden' value=$mannschaft->getId() required=true}
                              {include file='templateInputElement.tpl' name='mannschaftskategorieId' type='select' selectedElement=$mannschaft->getMannschaftskategorie()->getId() required=true bezeichnung='Mannschaftskategorie' selectValueList=$mannschaftkategorieList  }
                              {include file='templateInputElement.tpl' name='startnummer' type='number' value=$mannschaft->getStartnummer() required=true bezeichnung='Startnummer'}
                              {include file='templateInputElement.tpl' name='bezeichnung' type='text' value=$mannschaft->getMannschaft() required=true bezeichnung='Bezeichnung der Mannschaft'}
                              {include file='templateInputElement.tpl' name='ver_vorname' type='text' value=$mannschaft->getVer_vorname() required=false bezeichnung='Vorname des Verantworlichen'}
                              {include file='templateInputElement.tpl' name='ver_name' type='text' value=$mannschaft->getVer_name() required=false bezeichnung='Name des Verantworlichen'}
                              {include file='templateInputElement.tpl' name='ver_mail' type='email' value=$mannschaft->getVer_mail() required=false bezeichnung='E-Mail des Verantworlichen'}
                              {include file='templateInputElement.tpl' name='sendMannschaftData' type='submit'}
                         </form>

                            {if $mannschaft->getMitgliederList()->count() > 0}
                                <div class="form-group">
                                    <label class="form-label">Mitglieder der Mannschaft</label>
                                    <div class="col-sm-8">
                                        <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="dataTables-example">
                                            <tr>
                                                <th>StNr</th>
                                                <th>TP</th>
                                                <th>Name</th>
                                            </tr>
                                            {foreach from=$mannschaft->getMitgliederList() key=schluessel item=value}
                                                <tr>
                                                    <td>{$value->getStartnummer()}</td>
                                                    <td>{$value->getTransponder()}</td>
                                                    <td><a href="{$editTeilnehmerUrl}&action=search&id={$value->getId()}">{$value->getGesamtname()}</a></td>
                                                </tr>
                                            {/foreach}
                                        </table>
                                    </div>
                                </div>
                            {/if}
 
                       
            </div>
        </div>
    </div>
</div>