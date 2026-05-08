<div class="col-12 col-lg-6 mb-4">
    <div class="card shadow-sm border-0 h-100">
        <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0">
            <h4 class="card-title">Meldungen</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-borderless table-lg mb-0">
                    <thead class="text-muted">
                        <tr>
                            <th>Name</th>
                            <th>Nachricht</th>
                        </tr>
                    </thead>
                    <tbody>
                    {foreach from=$nachrichten key=schluessel item=value}
                        <tr>
                            <td class="col-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                                        <i class="fa fa-message"></i>
                                    </div>
                                    <p class="font-bold ms-3 mb-0 text-truncate">
                                        {$value['tn']->getVorname()} {$value['tn']->getName()}
                                    </p>
                                </div>
                            </td>
                            <td class="col-auto align-middle">
                                <span class="badge bg-info-subtle text-info border border-info-subtle text-wrap text-start">
                                    <span class="fw-bold">{$value['tn']->getAltersklasse()->getAltersklasseKurz()}:</span> {$value['text']}
                                </span>
                            </td>
                        </tr>	               
                    {/foreach}  
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>