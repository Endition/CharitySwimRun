<div class="row">
    <div class="col-12 card">
        <div class="card-body px-4 py-4-5">
        <h6>Prognose für max. 10h</h6>
            {foreach $stundenMeterList as $stunde => $meter}
                <div class="progress progress-striped active">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{$meter/$zielMeterFuerSpendensumme}" aria-valuemin="0" aria-valuemax="100" style="width: {$meter/$zielMeterFuerSpendensumme*100}%">
                        <span>In {$stunde} Stunden: {$meter/1000}km von {$zielMeterFuerSpendensumme/1000}km</span>
                    </div>
                </div>
            {/foreach}  
            Annahme für die Prognose: Durchschnittliche Meter pro Minute in der letzten stunde: {$meterProMinute}m
        </div>
    </div>
</div>