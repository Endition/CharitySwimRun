<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body px-4 py-4-5">
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-bold text-muted">Spendenstand ({date ( "d.m.Y, H:i:s" )} Uhr)</span>
                    <span class="fw-bold text-success">{$prozent}% erreicht</span>
                </div>
                <div class="progress progress-striped active rounded-pill mb-2" style="height: 1.5rem;">
                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="{$prozent}" aria-valuemin="0" aria-valuemax="100" style="width: {$prozent}%"></div>
                </div>
                <div class="text-end text-muted small">{$geld} Euro von {$spendensumme} Euro</div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-md-6 col-lg-3 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center">
                <div class="bg-primary rounded-circle text-white d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                    <i class="fa fa-2x fa-user"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Teilnehmer</h6>
                    <h3 class="fw-bolder mb-0">{$gestarteteTeilnehmer}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3 mb-4">
        <div class="card shadow-sm border-0 h-100"> 
            <div class="card-body d-flex align-items-center">
                <div class="bg-info rounded-circle text-white d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                    <i class="fa fa-2x fa-flag"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Meter (Rest)</h6>
                    <h3 class="fw-bolder mb-0">{$meter}m <span class="fs-6 fw-normal">({$restmeter}m)</span></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center">
                <div class="bg-success rounded-circle text-white d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                    <i class="fa fa-2x fa-person-swimming"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">{$streckenart}</h6>
                    <h3 class="fw-bolder mb-0">{$bahnen}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex align-items-center">
                <div class="bg-danger rounded-circle text-white d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                    <i class="fa fa-2x fa-money-bill"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1">Geld (Rest)</h6>
                    <h3 class="fw-bolder mb-0">{$geld}€ <span class="fs-6 fw-normal">({$restgeld}€)</span></h3>
                </div>
            </div>
        </div>
    </div>
</div>
