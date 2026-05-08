<div class="col-12 col-lg-6 mb-4">
    <div class="card shadow-sm border-0 h-100">
        <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0">
            <h4 class="card-title">Alarme</h4>
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
                    {foreach from=$teilnehmerWrongStreckeList item=teilnehmer}
                        <tr>
                            <td class="col-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                                        <i class="fa fa-bell"></i>
                                    </div>
                                    <p class="font-bold ms-3 mb-0 text-truncate">
                                        <a href="index.php?doc=teilnehmer&id={$teilnehmer->getId()}&action=search" class="text-decoration-none">{$teilnehmer->getGesamtname()}</a>
                                    </p>
                                </div>
                            </td>
                            <td class="col-auto align-middle">
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Keine Strecke zugeordnet</span>
                            </td>
                        </tr>
                    {/foreach}  
                    {foreach from=$teilnehmerWrongAltersklasseList item=teilnehmer}
                        <tr>
                            <td class="col-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                                        <i class="fa fa-bell"></i>
                                    </div>
                                    <p class="font-bold ms-3 mb-0 text-truncate">
                                        <a href="index.php?doc=teilnehmer&id={$teilnehmer->getId()}&action=search" class="text-decoration-none">{$teilnehmer->getGesamtname()}</a>
                                    </p>
                                </div>
                            </td>
                            <td class="col-auto align-middle">
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Keine Altersklasse zugeordnet</span>
                            </td>
                        </tr>
                    {/foreach}  
                    {foreach from=$teilnehmerWrongStartzeit1List item=teilnehmer}
                        <tr>
                            <td class="col-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                                        <i class="fa fa-bell"></i>
                                    </div>
                                    <p class="font-bold ms-3 mb-0 text-truncate">
                                        <a href="index.php?doc=teilnehmer&id={$teilnehmer->getId()}&action=search" class="text-decoration-none">{$teilnehmer->getGesamtname()}</a>
                                    </p>
                                </div>
                            </td>
                            <td class="col-auto align-middle">
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Startzeit in der Zukunft</span>
                            </td>
                        </tr>
                    {/foreach}  
                    {foreach from=$teilnehmerWrongStartzeit2List item=teilnehmer}
                        <tr>
                            <td class="col-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                                        <i class="fa fa-bell"></i>
                                    </div>
                                    <p class="font-bold ms-3 mb-0 text-truncate">
                                        <a href="index.php?doc=teilnehmer&id={$teilnehmer->getId()}&action=search" class="text-decoration-none">{$teilnehmer->getGesamtname()}</a>
                                    </p>
                                </div>
                            </td>
                            <td class="col-auto align-middle">
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle text-wrap text-start">Falsche Startzeit (Buchungen vor Start)</span>
                            </td>
                        </tr>
                    {/foreach}  	
                    {foreach from=$teilnehmerWrongTransponderList item=impuls}
                        <tr>
                            <td class="col-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                                        <i class="fa fa-bell"></i>
                                    </div>
                                    <p class="font-bold ms-3 mb-0 text-truncate">
                                        {$impuls->getTransponderId()} (Leser: {$impuls->getLeser()})
                                    </p>
                                </div>
                            </td>
                            <td class="col-auto align-middle">
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Kein Teilnehmer zugeordnet</span>
                            </td>
                        </tr>
                    {/foreach}  								
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>