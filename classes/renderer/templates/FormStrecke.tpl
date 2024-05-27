<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <h5 class="card-header">Strecke anlegen</h5>
            <div class="card-body">
                        <form role="form" name="StreckenForm" id="StreckenForm"
                              action="{$actionurl}" method="POST" class="form-horizontal">

                            {include file='templateInputElement.tpl' name='id' type='hidden' value=$strecke->getId() required=true}
                            {include file='templateInputElement.tpl' name='bezeichnungLang' type='text' value=$strecke->getBezLang() required=true bezeichnung='Bezeichnung der Strecke'}
                            {include file='templateInputElement.tpl' name='bezeichnungKurz' type='text' value=$strecke->getBezKurz() required=true bezeichnung='Abkürzung der Strecke' maxlength=5}
                            {include file='templateInputElement.tpl' name='sendStreckeData' type='submit'}
                        </form>
            </div>
        </div>
    </div>
</div>