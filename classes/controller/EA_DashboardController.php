<?php
namespace CharitySwimRun\classes\controller;

use DateTime;
use Doctrine\ORM\EntityManager;



class EA_DashboardController extends EA_Controller
{
    public function __construct( EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function getPageDashboard(): string
    {
        $content = "";

        $globaleLeistungsdaten = $this->EA_HitRepository->getGlobaleVeranstaltungsleistungsdaten(count($this->EA_StarterRepository->loadList(null,null,null,null,null,null,null,"id","ASC",true)),$this->konfiguration);

        $teilnehmerWrongStreckeList = $this->EA_StarterRepository->getDataCheckWrongStrecke();
        $teilnehmerWrongAltersklasseList = $this->EA_StarterRepository->getDataCheckWrongAltersklasse();
        $teilnehmerWrongTransponderList = $this->EA_StarterRepository->getDataCheckWrongTransponder();
        $teilnehmerWrongStartzeit1List = $this->EA_StarterRepository->getDataCheckWrongStartzeit1();
        $teilnehmerWrongStartzeit2List = $this->EA_StarterRepository->getDataCheckWrongStartzeit2();


        $content .= $this->EA_FR->getFormSpecialDaten($this->entityManager, $globaleLeistungsdaten);
        $content .= $this->getExtrapolration();
        $content .= "<div class='row'>";
        $content .= $this->EA_FR->getFormSpecialAlarm(
            $teilnehmerWrongStreckeList,
            $teilnehmerWrongAltersklasseList,
            $teilnehmerWrongTransponderList,
            $teilnehmerWrongStartzeit1List,
            $teilnehmerWrongStartzeit2List);
        $content .= $this->EA_FR->getFormSpecialNachrichten($this->EA_StarterRepository->loadInformationZielmarkeErreicht());
        $content .= "</div>";
        return $content;
    }

    private function getExtrapolration(): string
    {
        $start = $this->konfiguration->getStart();
        $ende = $this->konfiguration->getEnde();
        $jetzt = new DateTime();
        
        $startTs = $start->getTimestamp();
        $jetztTs = $jetzt->getTimestamp();
        $endeTs = $ende->getTimestamp();

        //Wenn Veranstaltung noch nicht begonnen hat, oder beende ist, nichts berechnen
        if($endeTs < $jetztTs || $startTs > $jetztTs){
            return "";
        }

        $diffStartToJetztInSeconds = $jetztTs - $startTs;
        $diffStartToJetztInMinutes = intval($diffStartToJetztInSeconds/60);
        $diffJetztToEndeInStunden = intval(($endeTs-$jetztTs)/60/60);

        $veranstaltungsDrittel =  intval($diffJetztToEndeInStunden/3);
        $fristThirdEnd = $veranstaltungsDrittel;
        $secondThirdEnde = $veranstaltungsDrittel*2;
        
        $factor = 1;
        
        //get meter
        $meter = $this->EA_HitRepository->getNumberOfEntries()*$this->konfiguration->getRundenlaenge();
        //get 100%
        $zielMeterFuerSpendensumme = $this->konfiguration->getGeld()/$this->konfiguration->getEuroprometer();
        //calc meter per minute
        $meterProMinute = intval($meter/$diffStartToJetztInMinutes);
        $stundenMeterList = [];
        for($i=1;$i<$diffJetztToEndeInStunden;$i++){
            //factor to consider lower performance at the beginnen and at the end
            $factor = ($i < $fristThirdEnd or $i > $secondThirdEnde) ? 0.8 : 1;
            //add meter for this hour
            $meter = $meter + intval(60*$meterProMinute*$factor);
            //save in array
            $stundenMeterList[$i] = $meter;
        }
        return $this->EA_R->getDashboardExtrapolration($stundenMeterList,$meterProMinute,$zielMeterFuerSpendensumme);

    }
}