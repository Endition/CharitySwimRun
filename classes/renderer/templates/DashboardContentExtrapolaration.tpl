<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body px-4 py-4-5">
                <h6 class="mb-4">Prognose für max. 10h</h6>
                {foreach $stundenMeterList as $stunde => $meter}
                    <div class="d-flex justify-content-between mb-1 mt-3">
                        <span class="fw-bold text-muted small">In {$stunde} Stunden</span>
                        <span class="fw-bold text-success small">{$meter/1000}km von {$zielMeterFuerSpendensumme/1000}km</span>
                    </div>
                    <div class="progress progress-striped active rounded-pill">
                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="{$meter/$zielMeterFuerSpendensumme}" aria-valuemin="0" aria-valuemax="100" style="width: {$meter/$zielMeterFuerSpendensumme*100}%"></div>
                    </div>
                {/foreach}  
                <p class="mt-4 text-muted small"><i class="fa fa-info-circle me-1"></i> Annahme für die Prognose: Durchschnittliche Meter pro Minute in der letzten Stunde: {$meterProMinute}m</p>
            </div>
        </div>
    </div>
</div>