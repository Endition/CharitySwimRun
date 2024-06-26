<?php
namespace CharitySwimRun\classes\controller;

use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use CharitySwimRun\classes\model\EA_AgeGroup;
use CharitySwimRun\classes\model\EA_Message;
use CharitySwimRun\classes\model\EA_Configuration;


class EA_AgeGroupController extends EA_Controller
{
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function getPageAltersklassen(): string
    {
        $content = "";
        $altersklasse = new EA_AgeGroup();

        if (isset($_POST['sendAltersklasseData'])) {
            $this->createAndUpdateAltersklasse();
        } elseif (isset($_GET['action']) && $_GET['action'] === "edit") {
            $altersklasse = $this->editAltersklasse();
        } elseif (isset($_GET['action']) && $_GET['action'] === "delete") {
            $this->deleteAltersklasse();
        } elseif(isset($_GET['action']) && $_GET['action'] === "sonderfunktionen"){
            $this->getSonderfunktionen();
        } elseif(isset($_POST['sendBerechneZuordnungNeu'])){
            $this->berechneZuordnungNeu();
        }else {
            $altersklasse = new EA_AgeGroup();
        }

        $content .= $this->getAltersklasseList();
        $content .= $this->EA_FR->getFormAltersklasse($this->entityManager, $altersklasse,$this->getYearAgeGroupRelation());
        return $content;
    }


    private function getSonderfunktionen(): void
    {
        if (isset($_POST['sendDLO2017Data']) || isset($_POST['sendPCSData'])) {
            $this->createAltersklasseFromDraft();
        } elseif (isset($_POST['sendBerechneZuordnungNeu'])) {
            $this->berechneZuordnungNeu();
        }
    }

    private function getYearAgeGroupRelation(): array
    {
        $altersklasseList = $this->EA_AgeGroupRepository->loadListOrderBy("uDatum");
        $yearToday = date('Y');
        $zuordnung = array_fill($yearToday  - 100, 101, []);
        if (count($altersklasseList) > 0) {
            for ($jahr = $yearToday - 100; $jahr <= $yearToday; $jahr++) {
                foreach ($altersklasseList as $altersklasse) {
                    if($this->konfiguration->getAltersklassen() === EA_Configuration::AGEGROUPMODUS_BIRTHYEAR && $altersklasse->getUDatum()){
                        if ($jahr >= $altersklasse->getUDatum()->format("Y") && $jahr <= $altersklasse->getODatum()->format("Y")) {
                            $zuordnung[$jahr][] .=  $altersklasse->getAltersklasseKurz();
                        }
                    }else{
                        $yearMinusStart = $yearToday - $altersklasse->getEndeAlter();
                        $yearMinusEnde = $yearToday -$altersklasse->getStartAlter();

                        if ($jahr >= $yearMinusStart && $jahr <= $yearMinusEnde) {
                            $zuordnung[$jahr][] = $altersklasse->getAltersklasseKurz();
                        }
                    }

                }
            }
        } 
        return $zuordnung;
    }

    private function createAltersklasseFromDraft(): void
    {
        $altersklasse = new EA_AgeGroup();
        if (isset($_POST['sendDLO2017Data'])) {
            $altersklasseVorlageList = $altersklasse->getAkBezGemDLO2017();
        } elseif (isset($_POST['sendPCSData'])) {
            $altersklasseVorlageList = $altersklasse->getAkBezGemPCS();
        }

        foreach ($altersklasseVorlageList as $singleak) {
            $altersklasse = new EA_AgeGroup();
            $altersklasse->setAltersklasse($singleak['Name']);
            $altersklasse->setAltersklasseKurz($singleak['Kurz']);
            $oDatum = date("Y") - $singleak['oDatum-minus'];
            $altersklasse->setODatum(new \DateTimeImmutable($oDatum."-12-31"));
            $udatum = date("Y") - $singleak['uDatum-minus'];
            $altersklasse->setUDatum(new \DateTimeImmutable($udatum."-01-01"));
            $altersklasse->setStartgeld($singleak['startgeld']);
            $altersklasse->setTpgeld($singleak['tpgeld']);
            $altersklasse->setUrkunde($singleak['Urkunde']);
            $altersklasse->setBronze($singleak['Bronze']);
            $altersklasse->setSilber($singleak['Silber']);
            $altersklasse->setGold($singleak['Gold']);
            $this->EA_AgeGroupRepository->create($altersklasse);
            $this->EA_Messages->addMessage( "Altersklasse " . $altersklasse->getAltersklasse() . " gespeichert",134542642224,EA_Message::MESSAGE_SUCCESS);            
        }
    }

    private function berechneZuordnungNeu(): void
    {
        $this->recalculateAltersklassen();
        $this->EA_StarterRepository->resetPlaetzeTeilnehmer();
    }

    private function createAndUpdateAltersklasse(): void
    {
        $id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);
        $bezeichnungLang = htmlspecialchars($_POST['altersklasse']);
        $bezeichnungKurz = htmlspecialchars($_POST['altersklasseKurz']);
        if($this->konfiguration->getAltersklassen() === EA_Configuration::AGEGROUPMODUS_BIRTHYEAR){
            $uDatum = htmlspecialchars($_POST['uDatum']);
            $oDatum  = htmlspecialchars($_POST['oDatum']);
            $StartAlter = null;
            $EndeAlter = null;
        }else{
            $uDatum = null;
            $oDatum  = null;
            $StartAlter = filter_input(INPUT_POST,'StartAlter',FILTER_SANITIZE_NUMBER_INT);
            $EndeAlter = filter_input(INPUT_POST,'EndeAlter',FILTER_SANITIZE_NUMBER_INT);
        }

        $startgeld = filter_input(INPUT_POST,'startgeld',FILTER_SANITIZE_NUMBER_FLOAT);
        $tpgeld = filter_input(INPUT_POST,'tpgeld',FILTER_SANITIZE_NUMBER_FLOAT);
        $wertungsschluessel = filter_input(INPUT_POST,'wertungsschluessel',FILTER_SANITIZE_NUMBER_FLOAT);
        $rekord = filter_input(INPUT_POST,'rekord',FILTER_SANITIZE_NUMBER_INT);
        $urkunde =filter_input(INPUT_POST,'urkunde',FILTER_SANITIZE_NUMBER_INT);
        $bronze = filter_input(INPUT_POST,'bronze',FILTER_SANITIZE_NUMBER_INT);
        $silber = filter_input(INPUT_POST,'silber',FILTER_SANITIZE_NUMBER_INT);
        $gold = filter_input(INPUT_POST,'gold',FILTER_SANITIZE_NUMBER_INT);

        //intinalize Object
        $altersklasse = ($id === null || $id === false || $id === "") ? new EA_AgeGroup() : $this->EA_AgeGroupRepository->loadById((int)$id);

        //checks for update und create case
        if($bezeichnungLang === ""){
            $this->EA_Messages->addMessage("Bezeichnung (lang) nicht ausgefüllt",1256897777,EA_Message::MESSAGE_ERROR);
            return;
        }

        if($bezeichnungKurz === ""){
            $this->EA_Messages->addMessage("Bezeichnung (kurz) nicht ausgefüllt",12576677777,EA_Message::MESSAGE_ERROR);
            return;
        }
        if($this->konfiguration->getAltersklassen() === EA_Configuration::AGEGROUPMODUS_BIRTHYEAR){
            if (DateTime::createFromFormat("Y-m-d", $uDatum . "-01-01") === false) {
                $this->EA_Messages->addMessage("Fehler beim Formt der unteren Jahresgrenze",162897098687,EA_Message::MESSAGE_ERROR);
            } else {
                $uDatum = new DateTimeImmutable($uDatum."-01-01");
            }

            if (DateTime::createFromFormat("Y-m-d", $oDatum . "-01-01") === false) {
                $this->EA_Messages->addMessage("Fehler beim Format der oberen Jahresgrenze",12523453242,EA_Message::MESSAGE_ERROR);
            } else {
                $oDatum = new DateTimeImmutable($oDatum."-12-31");
            }
        }
        if($this->konfiguration->getAltersklassen() === EA_Configuration::AGEGROUPMODUS_BIRTHYEAR){
            //Falls es falschrum eingegeben wird, einfach tauschen
            if ($oDatum < $uDatum) {
                $zwischenspeichern = $uDatum;
                $uDatum = $oDatum;
                $oDatum = $zwischenspeichern;
            }
        }
        //checks only for create case
        if($altersklasse->getId() === null){
            if($this->EA_AgeGroupRepository->isAvailable("altersklasse", $bezeichnungLang) === false){
                $this->EA_Messages->addMessage("Die Bezeichnung {$bezeichnungLang} für die Altersklasse ist schon vergeben",1459789787,EA_Message::MESSAGE_ERROR);
                return;
            }
            if($this->EA_AgeGroupRepository->isAvailable("altersklasseKurz", $bezeichnungKurz) === false){
                $this->EA_Messages->addMessage("Die Bezeichnung {$bezeichnungKurz} für die Altersklasse ist schon vergeben",1456787777,EA_Message::MESSAGE_ERROR);
                return;
            }
        }
        
        //set properties
        $altersklasse->setAltersklasse($bezeichnungLang);
        $altersklasse->setAltersklasseKurz($bezeichnungKurz);
        $altersklasse->setUDatum($uDatum);
        $altersklasse->setODatum($oDatum);
        $altersklasse->setStartAlter($StartAlter !== null ? $StartAlter : 0);
        $altersklasse->setEndeAlter($EndeAlter !== null ? $EndeAlter : 0);
        $altersklasse->setStartgeld($startgeld  !== null ? (float)$startgeld : 0);
        $altersklasse->setTpgeld($tpgeld !== null ? (float)$tpgeld : 0);
        $altersklasse->setWertungsschluessel($wertungsschluessel !== null ? (int)$wertungsschluessel : 0);
        $altersklasse->setRekord($rekord !== null ? (int)$rekord : 0);
        $altersklasse->setUrkunde($urkunde !== null ? (int)$urkunde : 0);
        $altersklasse->setBronze($bronze !== null ? (int)$bronze : 0);
        $altersklasse->setSilber($silber !== null ? (int)$silber : 0);
        $altersklasse->setGold($gold !== null ? (int)$gold : 0);

        
        //create case
        if($altersklasse->getId() === null){
            $this->EA_AgeGroupRepository->create($altersklasse);
            $this->EA_Messages->addMessage("Eintrag angelegt",193478775454,EA_Message::MESSAGE_SUCCESS);
        //update case
        }else{
            $this->EA_AgeGroupRepository->update();
            $this->EA_Messages->addMessage("Eintrag geändert",19233777772,EA_Message::MESSAGE_SUCCESS);
        }
        
    }

    private function editAltersklasse(): ?EA_AgeGroup
    {
        $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        $altersklasse = $this->EA_AgeGroupRepository->loadById($id);
        if($altersklasse === null){
            $this->EA_Messages->addMessage("Keine Altersklasse gefunden.",156567875,EA_Message::MESSAGE_WARNING);
        }
        return $altersklasse;
    }

    private function getAltersklasseList():  string
    {
        $content = "";
        $altersklasseList = $this->EA_AgeGroupRepository->loadList();
            $content = $this->EA_R->renderTabelleAltersklassen($altersklasseList, $this->EA_ConfigurationRepository->load());
        return $content;
    }

    private function deleteAltersklasse(): void
    {
        $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        $altersklasse = $this->EA_AgeGroupRepository->loadById($id);

        if($altersklasse === null){
            $this->EA_Messages->addMessage("Keine Altersklasse gefunden.",1956354634562,EA_Message::MESSAGE_ERROR);
            return;
        }
        if($this->EA_AgeGroupRepository->isInUse($altersklasse) === true){
            $this->EA_Messages->addMessage("Altersklasse ist noch in Benutzung.",18345345322,EA_Message::MESSAGE_ERROR);
            return;
        }

        $this->EA_AgeGroupRepository->delete($altersklasse);
        $this->recalculateAltersklassen();
        $this->EA_StarterRepository->resetPlaetzeTeilnehmer();

        $this->EA_Messages->addMessage("Altersklassenzuordnung neu berechnet.",173564523,EA_Message::MESSAGE_SUCCESS);
        $this->EA_Messages->addMessage("Altersklasse " . $altersklasse->getAltersklasse() . " gelöscht",1723452362,EA_Message::MESSAGE_SUCCESS);
    }

    private function recalculateAltersklassen(): void
    {
        $teilnehmerList = $this->EA_StarterRepository->loadList();
        foreach ($teilnehmerList as $tn) {
            $altersklasseAlt = $tn->getAltersklasse();
            $altersklasse = $this->EA_AgeGroupRepository->findByGeburtsjahr($tn->getGeburtsdatum());
            if($altersklasse === null){
                $this->EA_Messages->addMessage("Teilnehmer {$tn->getGesamtname()} {$tn->getGeburtsdatum()->format('d.m.Y')}  keine passende Altersklasse gefunden ",11723654343,EA_Message::MESSAGE_WARNING);
            }else{
                $tn->setAltersklasse($altersklasse);
                if($altersklasseAlt !== $altersklasse){
                    $this->EA_Messages->addMessage("Teilnehmer {$tn->getGesamtname()} Altersklasse neu berechnet ",1345775322747,EA_Message::MESSAGE_SUCCESS);
                }
            }
        }
        $this->EA_StarterRepository->update();
    }
}