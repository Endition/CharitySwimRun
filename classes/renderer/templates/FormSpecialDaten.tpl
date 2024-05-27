<div class="row">
    <div class="col-12 card">
        <div class="card-body px-4 py-4-5">
        Stand: {date ( "d.m.Y, H:i:s" )} ->  {$prozent}%
            <div class="progress progress-striped active">
                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{$prozent}" aria-valuemin="0" aria-valuemax="100" style="width: {$prozent}%">
                    <span>{$geld} Euro von {$spendensumme} Euro</span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
<div class="col-6 col-lg-3 col-md-6">
    <div class="card">
        <div class="card-body px-4 py-4-5">
            <div class="row">
                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                    <div class="stats-icon purple mb-2">
                        <i class="fa fa-2x fa-user" style="color: white;"></i>
                    </div>
                </div>
                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                    <h6 class="text-muted font-semibold">Teilnehmer</h6>
                    <h6 class="font-extrabold mb-0">{$gestarteteTeilnehmer}</h6>
                </div>
            </div> 
        </div>
    </div>
</div>
<div class="col-6 col-lg-3 col-md-6">
    <div class="card"> 
        <div class="card-body px-4 py-4-5">
            <div class="row">
                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                    <div class="stats-icon blue mb-2">
                        <i class="fa fa-2x fa-flag" style="color: white;"></i>
                    </div>
                </div>
                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                    <h6 class="text-muted font-semibold">Meter (Rest)</h6>
                    <h6 class="font-extrabold mb-0">{$meter}m ({$restmeter}m)</h6>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-6 col-lg-3 col-md-6">
    <div class="card">
        <div class="card-body px-4 py-4-5">
            <div class="row">
                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                    <div class="stats-icon green mb-2">
                        <i class="fa fa-2x fa-person-swimming" style="color: white;"></i>
                    </div>
                </div>
                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                    <h6 class="text-muted font-semibold">{$streckenart}</h6>
                    <h6 class="font-extrabold mb-0">{$bahnen}</h6>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-6 col-lg-3 col-md-6">
    <div class="card">
        <div class="card-body px-4 py-4-5">
            <div class="row">
                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                    <div class="stats-icon red mb-2">
                        <i class="fa fa-2x fa-money-bill" style="color: white;"></i>
                    </div>
                </div>
                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                    <h6 class="text-muted font-semibold">Geld (Rest)</h6>
                    <h6 class="font-extrabold mb-0">{$geld}€ ({$restgeld}€)</h6>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
