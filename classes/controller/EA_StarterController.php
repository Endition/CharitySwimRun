<?php
namespace CharitySwimRun\classes\controller;

use DateTimeImmutable;
use Doctrine\ORM\EntityManager;

use CharitySwimRun\classes\model\EA_Starter;
use CharitySwimRun\classes\model\EA_Configuration;
use CharitySwimRun\classes\model\EA_Club;

use CharitySwimRun\classes\model\EA_Message;



class EA_StarterController extends EA_Controller
{
    public function __construct( EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }
    

    public function getPageTeilnehmer(): string
    {
        $content = "";
        $teilnehmer = new EA_Starter();

        if (isset($_POST['sendTeilnehmerData'])) {
            $teilnehmer = $this->createAndUpdateTeilnehmer();
            $content .= $this->EA_FR->getFormTeilnehmer($this->entityManager, $teilnehmer, $this->konfiguration);
        } elseif (isset($_GET['action']) && $_GET['action'] === "edit") {
            $teilnehmer = $this->findTeilnehmer();
            $content .= $this->EA_FR->getFormTeilnehmer($this->entityManager, $teilnehmer, $this->konfiguration);
        } elseif (isset($_GET['action']) && $_GET['action'] === "delete") {
            $this->deleteTeilnehmer();
        } elseif (isset($_GET['action']) && $_GET['action'] === "search") {
            $teilnehmer = $this->findTeilnehmer();
            if($teilnehmer !== null){
                $content .= $this->EA_FR->getInfoTeilnehmer($this->entityManager, $teilnehmer);
                $content .= $this->EA_FR->getFormTeilnehmer($this->entityManager, $teilnehmer, $this->konfiguration);
            }

        } else {
            $teilnehmer = new EA_Starter();
            $content .= $this->EA_FR->getFormTeilnehmer($this->entityManager, $teilnehmer, $this->konfiguration);
        }
        return $content;
    }


    private function createAndUpdateTeilnehmer(): ?EA_Starter
    {
        $error = false;
        
        $idPost = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);
        $idGet = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        $id = null;
        if(is_numeric($idPost) && $idPost > 0){
            $id = (int)$idPost;
        }
        if(is_numeric($idGet) && $idGet > 0){
            $id = (int)$idGet;
        }

        $startnummer = filter_input(INPUT_POST,"startnummer",FILTER_SANITIZE_NUMBER_INT);
        $transponder = filter_input(INPUT_POST,"transponder",FILTER_SANITIZE_NUMBER_INT);
        $name = htmlspecialchars($_POST['name']); 
        $vorname = htmlspecialchars($_POST['vorname']); 
        $geburtsdatum = new DateTimeImmutable(htmlspecialchars($_POST['geburtsdatum']));
        $geschlecht = htmlspecialchars($_POST['geschlecht']); 
        
        $mannschaftId = filter_input(INPUT_POST,"mannschaft",FILTER_SANITIZE_NUMBER_INT);
        $mannschaft = $mannschaftId !== null ? $this->EA_TeamRepository->loadById($mannschaftId) : null;
        
        if (isset($_POST['verein']) && $_POST['verein'] !== "") {
            if(isset($_POST['vereinid']) && is_numeric($_POST['vereinid']) && $_POST['vereinid'] > 0){
                $verein = $this->EA_ClubRepository->loadById(filter_input(INPUT_POST,"vereinid",FILTER_SANITIZE_NUMBER_INT));
            }else{
                $verein = $this->EA_ClubRepository->loadByBezeichnung(htmlspecialchars($_POST['verein']));
                if($verein === null){
                    $verein = new EA_Club();
                    $verein->setVerein(htmlspecialchars($_POST['verein']));
                    $this->EA_ClubRepository->create($verein);
                }
            }
        } else {
            $verein = null;
        }

        $streckeId = filter_input(INPUT_POST,"strecke",FILTER_SANITIZE_NUMBER_INT);
        $strecke = $this->EA_DistanceRepository ->loadById($streckeId);

        $startgruppe = isset($_POST['startgruppe']) ? filter_input(INPUT_POST,"startgruppe",FILTER_SANITIZE_NUMBER_INT) : 0;
        $mail = isset($_POST['mail']) ? htmlspecialchars($_POST['mail']) : ""; 
        $plz = isset($_POST['plz']) ? htmlspecialchars($_POST['plz']) : "";  
        $wohnort = isset($_POST['wohnort']) ? htmlspecialchars($_POST['wohnort']) : ""; 
        $strasse = isset($_POST['strasse']) ?  htmlspecialchars($_POST['strasse']) : "";  
        $status = filter_input(INPUT_POST,"status",FILTER_SANITIZE_NUMBER_INT);
        $startzeit = ($this->konfiguration->getStarttyp() === "aba" && isset($_POST['sendTeilnehmerData']) && !is_numeric($id) ) ? new DateTimeImmutable() : null;


        //intinalize Object
        $teilnehmer = ($id === null) ? new EA_Starter() : $this->EA_StarterRepository->loadById($id);

        //checks for update und create case


        //checks only for create case
        if($teilnehmer->getId() === null){
            //Doppelte Startnummer
            if ($this->EA_StarterRepository->loadByStartnummer($startnummer) !== null) {
                $this->EA_Messages->addMessage("Diese Startnummer gibt es schon.",156353778,EA_Message::MESSAGE_ERROR);
                $error = true;

            }
            if ($this->konfiguration->getTransponder() === EA_Configuration::TRANSPONDER_YES) {
                //Doppelter Transponder
                if ($this->EA_StarterRepository->loadByTransponder($transponder) !== null) {
                    $this->EA_Messages->addMessage("Dieser Transponder ist schon in Benutzung.",144357357,EA_Message::MESSAGE_ERROR);
                    $error = true;
                }
            }
        //checks only for update case
        }else{
            //Doppelte Startnummer
            if ($this->EA_StarterRepository->loadByFilter($teilnehmer->getId(),$startnummer) !== null) {
                $this->EA_Messages->addMessage("Diese Startnummer gibt es schon.",12353472373,EA_Message::MESSAGE_ERROR);
                $error = true;
            }
            //Doppelter Transponder
            //Es ist möglich dass der Transponder schon ausgebrucht ist.
            if ($this->konfiguration->getTransponder() === EA_Configuration::TRANSPONDER_YES) {
                if ($transponder > 0) {
                    if ($this->EA_StarterRepository->loadByFilter($teilnehmer->getId(),null,$transponder) !== null) {
                        $this->EA_Messages->addMessage("Dieser Transponder ist schon in Benutzung.",13747733754,EA_Message::MESSAGE_ERROR);
                        $error = true;
                    }
                }
            }
        }
        
        //set properties
        $teilnehmer->setId($id);
        $teilnehmer->setStartnummer((int)$startnummer);
        $teilnehmer->setTransponder((int)$transponder);
        $teilnehmer->setName($name);
        $teilnehmer->setVorname($vorname);
        $teilnehmer->setGeburtsdatum($geburtsdatum);

        if ($this->konfiguration->getAltersklassen() === EA_Configuration::AGEGROUPMODUS_AGE) {
            $altersklasse = $this->EA_AgeGroupRepository->findByAlter($geburtsdatum, $this->konfiguration ->getEnde());
        } else {
            $altersklasse = $this->EA_AgeGroupRepository->findByGeburtsjahr($teilnehmer->getGeburtsdatum());
        }

        if ($altersklasse === null) {
            $this->EA_Messages->addMessage("Keine passende Altersklasse gefunden.",131464654654,EA_Message::MESSAGE_ERROR);
            $error = true;
        }
        $teilnehmer->setAltersklasse($altersklasse);
        $teilnehmer->setGeschlecht($geschlecht);
        $teilnehmer->setMannschaft($mannschaft);
        $teilnehmer->setVerein($verein);
        $teilnehmer->setStrecke($strecke);
        $teilnehmer->setStartgruppe($startgruppe);
        $teilnehmer->setMail($mail);
        $teilnehmer->setPlz((int)$plz);
        $teilnehmer->setWohnort($wohnort);
        $teilnehmer->setStrasse($strasse);
        $teilnehmer->setStatus($status);
        $teilnehmer->setKonfiguration($this->konfiguration);
        if($startzeit !== null){
            $teilnehmer->setStartzeit($startzeit);
        }

        if($error === true){
            return $teilnehmer;
        }
        
        //create case
        if($teilnehmer->getId() === null){
            $this->EA_StarterRepository->create($teilnehmer);
            $this->EA_Messages->addMessage("Teilnehmer {$teilnehmer->getGesamtname()} angelegt. Das Stargeld beträgt: {$teilnehmer->getAltersklasse()->getStartgeld()} Euro und der Pfand beträgt {$teilnehmer->getAltersklasse()->getTpgeld()} Euro",1233532372,EA_Message::MESSAGE_SUCCESS);
        //update case
        }else{
            $this->EA_StarterRepository->update();
            $this->EA_StarterRepository->resetPlaetzeTeilnehmer($teilnehmer);
            $this->EA_Messages->addMessage("Teilnehmer {$teilnehmer->getGesamtname()} geändert",1897342372,EA_Message::MESSAGE_SUCCESS);
        }
        return $teilnehmer;
    }

    private function findTeilnehmer(): ?EA_Starter
    {
        $idPost = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);
        $idGet = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        $suchText = htmlspecialchars($_POST['TeilnehmerSuchen']);
        $id = null;
        $teilnehmer = null;

        if(is_numeric($idPost) && $idPost > 0){
            $id 
            = (int)$idPost;
        }
        if(is_numeric($idGet) && $idGet > 0){
            $id = (int)$idGet;
        }

        if($id !== null){
            $teilnehmer = $this->EA_StarterRepository->loadById($id);
        }

        //only backup, suchText = Startnummer?
        if($teilnehmer === null && $suchText !== ""){
            $teilnehmer = $this->EA_StarterRepository->loadByFilter(null,(int)$suchText);
        }

        if($teilnehmer === null){
            $this->EA_Messages->addMessage("Keine Teilnehmer gefunden.",156567875,EA_Message::MESSAGE_WARNING);
        }

        return $teilnehmer;
    }


    private function deleteTeilnehmer(): void
    {
        $idPost = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);
        $idGet = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);
        $id = is_numeric($idPost) ? $idPost : $idGet;

        $teilnehmer = $this->EA_StarterRepository->loadById($id);

        if($teilnehmer === null){
            $this->EA_Messages->addMessage("Keine Teilnehmer gefunden.",135775477,EA_Message::MESSAGE_ERROR);
            return;
        }
        $timestampminus3minuten = time() - 180;
        if ($teilnehmer->getletzteBuchung("U") > $timestampminus3minuten) {
            $this->EA_Messages->addMessage("Dieser Teilnehmer ist eben noch geschwommen. Bitte noch warten.",15372377477,EA_Message::MESSAGE_ERROR);
            return;
        }
        $this->EA_StarterRepository->delete($teilnehmer);
        $this->EA_Messages->addMessage("Teilnehmer " . $teilnehmer->getGesamtname() . " gelöscht",1723452362,EA_Message::MESSAGE_SUCCESS);
    }

}