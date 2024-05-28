<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <h5 class="card-header">Teilnehmer: <b>{$EA_Starter->getVorname()} {$EA_Starter->getName()}</b></h5>
            <div class="card-body">
                <div class="row">	
                    <div class="col-md-6 col-lg-3">
                        <div class="card">
                            <h5 class="card-header">Grunddaten</h5>
                            <div class="card-body">
                                <div class="row"><label class="col-md-6 col-lg-6">Startnummer</label><div class="col-md-6 col-lg-6  ">{$EA_Starter->getStartnummer()}</div></div>
                                    {if $konfiguration->getTransponder() eq true}	
                                    <div class="row"><label class="col-md-6 col-lg-6">Transponder</label><div class="col-md-6 col-lg-6 ">{$EA_Starter->getTransponder()}</div></div>
                                    {/if}
                                <div class="row"><label class="col-md-6 col-lg-6">Name</label><div class="col-md-6 col-lg-6 " style="color:red">{$EA_Starter->getVorname()} {$EA_Starter->getName()}</div></div>
                                <div class="row"><label class="col-md-6 col-lg-6">Geschlecht</label><div class="col-md-6 col-lg-6 ">{$EA_Starter->getGeschlecht()}</div></div>
                                <div class="row"><label class="col-md-6 col-lg-6">Geburtsdatum</label><div class="col-md-6 col-lg-6 ">{$EA_Starter->getGeburtsdatum()->format("d.m.Y.")}</div></div>
                                <div class="row"><label class="col-md-6 col-lg-6">Startgeld</label><div class="col-md-6 col-lg-6 ">{$EA_Starter->getStartgeld()} € zzgl. {$EA_Starter->getTranspondergeld()} € Pfand </div></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card">
                            <h5 class="card-header">erweiterte Daten</h5>
                            <div class="card-body">
                                <div class="row"><label class="col-md-6 col-lg-6">Adresse</label><p class="col-md-6 col-lg-6">{$EA_Starter->getPLZ()} {$EA_Starter->getWohnort()} {$EA_Starter->getStrasse()}</p></div>
                                <div class="row"><label class="col-md-6 col-lg-6">Strecke</label><p class="col-md-6 col-lg-6">{$EA_Starter->getStrecke()->getBezKurz()}</p></div>
                                <div class="row"><label class="col-md-6 col-lg-6">Altersklasse</label><p class="col-md-6 col-lg-6">{$EA_Starter->getAltersklasse()->getAltersklasseKurz()}</p></div>
                                <div class="row"><label class="col-md-6 col-lg-6">Mannschaft</label><p class="col-md-6 col-lg-6">{$EA_Starter->getMannschaft()->getMannschaft()}</p></div>
                                <div class="row"><label class="col-md-6 col-lg-6">Startgruppe</label><p class="col-md-6 col-lg-6">{$EA_Starter->getStartgruppe()}</p></div>
                                <div class="row"><label class="col-md-6 col-lg-6">Verein</label><p class="col-md-6  col-lg-6">{$EA_Starter->getVerein()->getVerein()}</p></div>
                                <div class="row"><div class="col-lg-12" style="text-align: center;"><a href="index.php?doc=teilnehmer&action=search&id={$EA_Starter->getId()}" class="btn btn-primary"><i class="fa fa-user fa-fw"></i> Teilnehmer bearbeiten</a></div></div>
                            </div>
                        </div>
                    </div>	
                    <div class="col-md-6 col-lg-3">
                        <div class="card">
                            <h5 class="card-header">Leistungen</h5>
                            <div class="card-body">
                                <div class="row"><label class="col-md-6 col-lg-6">{$konfiguration->getStreckenart()}</label><p class="col-md-6 col-lg-6">{$EA_Starter->getStreckenart()}</p></div>
                                <div class="row"><label class="col-md-6 col-lg-6">Meter (Gesamt)</label><p class="col-md-6 col-lg-6">{$EA_Starter->getMeter()} m</p></div>
                                <div class="row"><label class="col-md-6 col-lg-6">Geld</label><p class="col-md-6 col-lg-6">{$EA_Starter->getGeld()} Euro</p></div>
                                <div class="row"><label class="col-md-6 col-lg-6">Wertung</label><p class="col-md-6 col-lg-6">{$EA_Starter->getWertung("lang")}</p></div>
                                <div class="row"><label class="col-md-6 col-lg-6">nächste Wertung:</label><p class="col-md-6 col-lg-6">{$EA_Starter->getNaechsteWertung()} ({$EA_Starter->getNaechsteWertungStreckenart($konfiguration->getRundenlaenge())} <small>Impulse</small>)</p></div>
                                <div class="row"><div class="col-md-6 col-lg-12" style="text-align: center;"><a href="service.php?doc=urkunden&action=drucken&id={$EA_Starter->getId()}" class="btn btn-primary"><i class="fa fa-file fa-fw"></i> Urkunde drucken</a></div></div>
                            </div>
                        </div></div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card">
                            <h5 class="card-header">Zeiten</h5>
                            <div class="card-body">
                                <div class="row"><label class="col-md-6 col-lg-6">Startzeit</label><p class="col-md-6 col-lg-6">{$EA_Starter->getStartzeit()->format("d.m.Y H:i:s")}</p></div>
                                <div class="row"><label class="col-md-6 col-lg-6">Gesamtzeit</label><p class="col-md-6 col-lg-6">{$EA_Starter->getGesamtzeit()}</p></div>
                                <div class="row"><label class="col-md-6 col-lg-6">letzte Buchung</label><p class="col-md-6 col-lg-6">{$EA_Starter->getletzteBuchung()}</p></div>
                                <div class="row"><div class="col-lg-12" style="text-align: center;"><a href="index.php?doc=startzeiten" class="btn btn-primary"><i class="fa fa-clock fa-fw"></i> Startzeit bearbeiten</a></div></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">	
                    <div class="col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                {foreach from=$stati key=schluessel  item=Status}
                                    <span {if $EA_Starter->getStatus() >= $schluessel} style="background-color:#5CB85C" {/if}> {$Status} {if $EA_Starter->getStatus() >= $schluessel}<i class="fa fa-check-square"></i> {/if} </span>  {if $schluessel < 99}  <i class="fa fa-arrow-right"></i>{/if}
                                {/foreach}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>