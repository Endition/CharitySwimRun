<div class="card">
<h5 class="card-header">Teilnehmer: <b>{$EA_Starter->getVorname()} {$EA_Starter->getName()}</b> angemeldet</h5>
<div class="card-body">
    <div class="row"><label class="col-md-6 col-lg-6">Startnummer</label><div class="col-md-6 col-lg-6  ">{$EA_Starter->getStartnummer()}</div></div>
        {if $konfiguration->getTransponder() eq true}	
        <div class="row"><label class="col-md-6 col-lg-6">Transponder</label><div class="col-md-6 col-lg-6 ">{$EA_Starter->getTransponder()}</div></div>
        {/if}
    <div class="row"><label class="col-md-6 col-lg-6">Name</label><div class="col-md-6 col-lg-6 " style="color:red">{$EA_Starter->getVorname()} {$EA_Starter->getName()}</div></div>
    <div class="row"><label class="col-md-6 col-lg-6">Geschlecht</label><div class="col-md-6 col-lg-6 ">{$EA_Starter->getGeschlecht()}</div></div>
    <div class="row"><label class="col-md-6 col-lg-6">Geburtsdatum</label><div class="col-md-6 col-lg-6 ">{$EA_Starter->getGeburtsdatum()->format("d.m.Y")}</div></div>
    <div class="row"><label class="col-md-6 col-lg-6">Adresse</label><p class="col-md-6 col-lg-6">{$EA_Starter->getPLZ()} {$EA_Starter->getWohnort()} {$EA_Starter->getStrasse()}</p></div>
    <div class="row"><label class="col-md-6 col-lg-6">Strecke</label><p class="col-md-6 col-lg-6">{$EA_Starter->getStrecke()->getBezKurz()}</p></div>
    <div class="row"><label class="col-md-6 col-lg-6">Altersklasse</label><p class="col-md-6 col-lg-6">{$EA_Starter->getAltersklasse()->getAltersklasseKurz()}</p></div>
    <div class="row"><label class="col-md-6 col-lg-6">Mannschaft</label><p class="col-md-6 col-lg-6">{$EA_Starter->getMannschaft()->getMannschaft()}</p></div>
    <div class="row"><label class="col-md-6 col-lg-6">Startgruppe</label><p class="col-md-6 col-lg-6">{$EA_Starter->getStartgruppe()}</p></div>
    <div class="row"><label class="col-md-6 col-lg-6">Verein</label><p class="col-md-6  col-lg-6">{$EA_Starter->getVerein()->getVerein()}</p></div>
</div>
</div>

<div class="card">
<h5 class="card-header">Nächster Schritt</h5>
    <div class="card-body">
            <h1>Bitte gehen Sie jetzt zu Anmeldung. Halten Sie das Startgeld von {$EA_Starter->getStartgeld()} € zzgl. {$EA_Starter->getTranspondergeld()} € Pfand </h1>
    </div>
</div>

<div class="d-grid gap-2">
<a class="btn btn-primary btn-lg" href="index.php?doc=selbstanmeldung">beenden bzw. Neue Anmeldung eingeben</a>
<script type="text/javascript">
window.setTimeout('window.location = "index.php?doc=selbstanmeldung"',10000);
</script>
</div>
