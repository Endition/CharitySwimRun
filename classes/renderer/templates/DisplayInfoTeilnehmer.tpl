<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <h5 class="card-header">Teilnehmer: <b>{$EA_T->getVorname()} {$EA_T->getName()}</b></h5>
            <div class="card-body">
                <div class="row">	
                    <div class="col-md-6 col-lg-3">
                        <div class="card">
                            <h5 class="card-header">Grunddaten</h5>
                            <div class="card-body">
                                <div class="row"><label class="col-md-6 col-lg-6">Startnummer</label><div class="col-md-6 col-lg-6  ">{$EA_T->getStartnummer()}</div></div>
                                    {if $konfiguration->getTransponder() eq true}	
                                    <div class="row"><label class="col-md-6 col-lg-6">Transponder</label><div class="col-md-6 col-lg-6 ">{$EA_T->getTransponder()}</div></div>
                                    {/if}
                                <div class="row"><label class="col-md-6 col-lg-6">Name</label><div class="col-md-6 col-lg-6 " style="color:red">{$EA_T->getVorname()} {$EA_T->getName()}</div></div>
                                <div class="row"><label class="col-md-6 col-lg-6">Geschlecht</label><div class="col-md-6 col-lg-6 ">{$EA_T->getGeschlecht()}</div></div>
                                <div class="row"><label class="col-md-6 col-lg-6">Geburtsdatum</label><div class="col-md-6 col-lg-6 ">{$EA_T->getGeburtsdatum()->format("d.m.Y.")}</div></div>
                                <div class="row"><label class="col-md-6 col-lg-6">Startgeld</label><div class="col-md-6 col-lg-6 ">{$EA_T->getStartgeld()} € zzgl. {$EA_T->getTranspondergeld()} € Pfand </div></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card">
                            <h5 class="card-header">erweiterte Daten</h5>
                            <div class="card-body">
                                <div class="row"><label class="col-md-6 col-lg-6">Adresse</label><p class="col-md-6 col-lg-6">{$EA_T->getPLZ()} {$EA_T->getWohnort()} {$EA_T->getStrasse()}</p></div>
                                <div class="row"><label class="col-md-6 col-lg-6">Strecke</label><p class="col-md-6 col-lg-6">{$EA_T->getStrecke()->getBezKurz()}</p></div>
                                <div class="row"><label class="col-md-6 col-lg-6">Altersklasse</label><p class="col-md-6 col-lg-6">{$EA_T->getAltersklasse()->getAltersklasseKurz()}</p></div>
                                <div class="row"><label class="col-md-6 col-lg-6">Mannschaft</label><p class="col-md-6 col-lg-6">{$EA_T->getMannschaft()->getMannschaft()}</p></div>
                                <div class="row"><label class="col-md-6 col-lg-6">Startgruppe</label><p class="col-md-6 col-lg-6">{$EA_T->getStartgruppe()}</p></div>
                                <div class="row"><label class="col-md-6 col-lg-6">Verein</label><p class="col-md-6  col-lg-6">{$EA_T->getVerein()->getVerein()}</p></div>
                                <div class="row"><div class="col-lg-12" style="text-align: center;"><a href="index.php?doc=teilnehmer&action=search&id={$EA_T->getId()}" class="btn btn-primary"><i class="fa fa-user fa-fw"></i> Teilnehmer bearbeiten</a></div></div>
                            </div>
                        </div>
                    </div>	
                    <div class="col-md-6 col-lg-3">
                        <div class="card">
                            <h5 class="card-header">Leistungen</h5>
                            <div class="card-body">
                                <div class="row"><label class="col-md-6 col-lg-6">{$konfiguration->getStreckenart()}</label><p class="col-md-6 col-lg-6">{$EA_T->getStreckenart()}</p></div>
                                <div class="row"><label class="col-md-6 col-lg-6">Meter (Gesamt)</label><p class="col-md-6 col-lg-6">{$EA_T->getMeter()} m</p></div>
                                <div class="row"><label class="col-md-6 col-lg-6">Geld</label><p class="col-md-6 col-lg-6">{$EA_T->getGeld()} Euro</p></div>
                                <div class="row"><label class="col-md-6 col-lg-6">Wertung</label><p class="col-md-6 col-lg-6">{$EA_T->getWertung("lang")}</p></div>
                                <div class="row"><label class="col-md-6 col-lg-6">nächste Wertung:</label><p class="col-md-6 col-lg-6">{$EA_T->getNaechsteWertung()} ({$EA_T->getNaechsteWertungStreckenart($konfiguration->getRundenlaenge())} <small>Impulse</small>)</p></div>
                                <div class="row"><div class="col-md-6 col-lg-12" style="text-align: center;"><a href="service.php?doc=urkunden&action=drucken&id={$EA_T->getId()}" class="btn btn-primary"><i class="fa fa-file fa-fw"></i> Urkunde drucken</a></div></div>
                            </div>
                        </div></div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card">
                            <h5 class="card-header">Zeiten</h5>
                            <div class="card-body">
                                <div class="row"><label class="col-md-6 col-lg-6">Startzeit</label><p class="col-md-6 col-lg-6">{$EA_T->getStartzeit()->format("d.m.Y H:i:s")}</p></div>
                                <div class="row"><label class="col-md-6 col-lg-6">Gesamtzeit</label><p class="col-md-6 col-lg-6">{$EA_T->getGesamtzeit()}</p></div>
                                <div class="row"><label class="col-md-6 col-lg-6">letzte Buchung</label><p class="col-md-6 col-lg-6">{$EA_T->getletzteBuchung()}</p></div>
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
                                    <span {if $EA_T->getStatus() >= $schluessel} style="background-color:#5CB85C" {/if}> {$Status} {if $EA_T->getStatus() >= $schluessel}<i class="fa fa-check-square"></i> {/if} </span>  {if $schluessel < 99}  <i class="fa fa-arrow-right"></i>{/if}
                                {/foreach}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>