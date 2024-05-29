<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <h5 class="card-header">
                Starter: <b>{$EA_Starter->getGesamtname()}</b>            
                <button class="btn btn-sm btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target=".multi-collapse" aria-expanded="false" aria-controls="starterDetailView1 starterDetailView2 starterDetailView3">
                    Details einblenden
                </button>
            </h5>

            <div class="card-body">
                <div class="row">	
                    <div class="col-2">
                       <b> Startnummer</b>:<br/> {$EA_Starter->getStartnummer()}
                    </div>
                    {if $konfiguration->getTransponder() eq true}	
                        <div class="col-2">
                            <b>Transponder</b>:<br/> {$EA_Starter->getTransponder()}
                        </div>
                    {/if}
                    <div class="col-2">
                        <b>Geschlecht</b>:<br/> {$EA_Starter->getGeschlecht()}
                    </div>
                    <div class="col-2">
                       <b> Geburtsdatum</b>:<br/> {$EA_Starter->getGeburtsdatum()->format("d.m.Y.")}
                    </div>
                    <div class="col-2">
                        <b>Startgeld</b>:<br/> {$EA_Starter->getStartgeld()} € zzgl. {$EA_Starter->getTranspondergeld()} € Pfand 
                    </div>
                    <div class="col-2">
                        <a href="index.php?doc=teilnehmer&action=search&id={$EA_Starter->getId()}" class="btn btn-warning"><i class="fa fa-user fa-fw"></i>bearbeiten</a>
                    </div>
                    <hr>
                </div>
                
                <div class="row collapse  multi-collapse" id="starterDetailView1">	
                    <div class="col-2">
                        <b>Strecke</b>:<br/> {$EA_Starter->getStrecke()}
                    </div>
                        <div class="col-2">
                            <b>Altersklasse </b>:<br/> {$EA_Starter->getAltersklasse()}
                        </div>
                    <div class="col-2">
                        <b>Mannschaft</b>:<br/> {$EA_Starter->getMannschaft()}
                    </div>
                    <div class="col-2">
                        <b>Startgruppe</b>:<br/> {$EA_Starter->getStartgruppe()}
                    </div>
                    <div class="col-2">
                       <b> Verein</b>:<br/> {$EA_Starter->getVerein() } 
                    </div>
                    <div class="col-2">
                    </div>
                    <hr>
                </div>
                
                <div class="row">	
                    <div class="col-2">
                    <b>{$konfiguration->getStreckenart()} </b>:<br/> {$EA_Starter->getStreckenart()}
                    </div>
                        <div class="col-2">
                        <b> Meter </b>:<br/> {$EA_Starter->getMeter()}m
                        </div>
                    <div class="col-2">
                    <b>Geld </b>:<br/> {$EA_Starter->getGeld()}€
                    </div>
                    <div class="col-2">
                    <b>Wertung </b>:<br/> {$EA_Starter->getWertung("lang")}
                    </div>
                    <div class="col-2">
                    <b>nächste Wertung </b>:<br/> {$EA_Starter->getNaechsteWertung()} ({$EA_Starter->getNaechsteWertungStreckenart($konfiguration->getRundenlaenge())} <small>Impulse</small>)
                    </div>
                    <div class="col-2">
                        <a href="service.php?doc=urkunden&action=drucken&id={$EA_Starter->getId()}" class="btn btn-warning"><i class="fa fa-file fa-fw"></i>drucken</a>
                    </div>
                    <hr>
                </div>
               
                <div class="row collapse  multi-collapse" id="starterDetailView2">	
                    <div class="col-2">
                    <b> Startzeit  </b>:<br/>  {$EA_Starter->getStartzeit()->format("d.m.Y H:i:s")}
                    </div>
                        <div class="col-2">
                        <b> Gesamtzeit </b>:<br/> {$EA_Starter->getGesamtzeit()}
                        </div>
                    <div class="col-2">
                    <b> letzte Buchung </b>:<br/> {$EA_Starter->getletzteBuchung()}
                    </div>
                    <div class="col-2">
                    </div>
                    <div class="col-2">
                    </div>
                    <div class="col-2">
                        <a href="index.php?doc=startzeiten" class="btn btn-primary"><i class="fa fa-clock fa-fw"></i>bearbeiten</a>
                    </div>
                    <hr>
                </div>
                
                <div class="row collapse  multi-collapse" id="starterDetailView3">	
                    <div class="col-12">
                                {foreach from=$stati key=schluessel  item=Status}
                                    <span {if $EA_Starter->getStatus() >= $schluessel} style="background-color:#5CB85C" {/if}> {$Status} {if $EA_Starter->getStatus() >= $schluessel}<i class="fa fa-check-square"></i> {/if} </span>  {if $schluessel < 99}  <i class="fa fa-arrow-right"></i>{/if}
                                {/foreach}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>