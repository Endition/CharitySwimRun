<?php
namespace CharitySwimRun\classes\controller;

use Doctrine\ORM\EntityManager;
use CharitySwimRun\classes\model\EA_Starter;
use CharitySwimRun\classes\model\EA_Club;
use CharitySwimRun\classes\model\EA_Message;
use CharitySwimRun\classes\model\EA_Configuration;

class EA_SelfCheckInController extends EA_Controller
{

    public function __construct( EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }
    
    public function getPageSelbstanmeldung(): string
    {
        $content = "";
        $error = true;

        if (isset($_POST['sendeSelbstanmeldung'])) {
            $EA_Starter = $this->initiateTeilnehmer();
            $error = $this->securityChecksTeilnehmer($EA_Starter);

            if ($error === false) {
                $this->EA_StarterRepository->create($EA_Starter);
                $content .= $this->EA_FR->getInfoSelbstanmeldung($this->entityManager, $EA_Starter);
                $EA_Starter = new EA_Starter();
            }
        }else{
            $content .= $this->EA_FR->getFormSelbstanmeldung($this->entityManager, $this->konfiguration);
        }
        $content = $this->EA_Messages->renderMessageAlertList() . " " . $content;

        return $content;
    }

    
    private function initiateTeilnehmer(): ?EA_Starter
    {
        $EA_Starter = new EA_Starter();

        $startnummer = rand(9000,10000);
        $transponder = $startnummer;
        $name = htmlspecialchars($_POST['name']);
        $vorname = htmlspecialchars($_POST['vorname']);
        $geburtsdatum = (isset($_POST['geburtsdatum'])) ? new \DateTimeImmutable(htmlspecialchars($_POST['geburtsdatum'])) : null;
        $geschlecht = htmlspecialchars($_POST['geschlecht']);
        $mannschaft = (isset($_POST['mannschaft']) && $_POST['mannschaft'] > 0) ? $this->EA_StarterRepository->loadById(htmlspecialchars($_POST['mannschaft'])) : null;
        if (isset($_POST['verein']) && $_POST['verein'] !== "") {
            $verein = $this->EA_ClubRepository->loadByBezeichnung(htmlspecialchars($_POST['verein']));
            if($verein === null){
                $verein = new EA_Club();
                $verein->setVerein(htmlspecialchars($_POST['verein']));
                $this->EA_ClubRepository->create($verein);
            }
        } elseif (isset($_POST['vereinid']) && is_numeric($_POST['vereinid']) && $_POST['vereinid'] > 0) {
            $verein = $this->EA_ClubRepository->loadById(htmlspecialchars($_POST['vereinid']));
        } else {
            $verein = null;
        }
        $strecke = (isset($_POST['strecke'])) ? $this->EA_DistanceRepository->loadById($_POST['strecke']) :null;
        $startgruppe = (isset($_POST['startgruppe'])) ? htmlspecialchars($_POST['startgruppe']) : 0;
        $mail = (isset($_POST['mail'])) ? htmlspecialchars($_POST['mail']) : "";
        $plz = (isset($_POST['plz'])) ? htmlspecialchars($_POST['plz']) : null;
        $wohnort = (isset($_POST['wohnort'])) ? htmlspecialchars($_POST['wohnort']) : "";
        $strasse = (isset($_POST['strasse'])) ? htmlspecialchars($_POST['strasse']) : "";
        $startzeit = ($this->konfiguration->getStarttyp() === "aba") ? new \DateTimeImmutable() : null;

        $EA_Starter->setStartnummer($startnummer);
        $EA_Starter->setTransponder($transponder);
        $EA_Starter->setName($name);
        $EA_Starter->setVorname($vorname);
        $EA_Starter->setGeburtsdatum($geburtsdatum);

        if ($this->konfiguration->getAltersklassen() === EA_Configuration::AGEGROUPMODUS_AGE) {
            $altersklasse = $this->EA_AgeGroupRepository->findByAlter($geburtsdatum, $this->konfiguration->getEnde());
        } else {
            $altersklasse = $this->EA_AgeGroupRepository->findByGeburtsjahr($EA_Starter->getGeburtsdatum());
        }
        $EA_Starter->setAltersklasse($altersklasse);
        $EA_Starter->setGeschlecht($geschlecht);
        $EA_Starter->setMannschaft($mannschaft);
        $EA_Starter->setVerein($verein);
        $EA_Starter->setStrecke($strecke);
        $EA_Starter->setStartgruppe($startgruppe);
        $EA_Starter->setMail($mail);
        $EA_Starter->setPlz($plz);
        $EA_Starter->setWohnort($wohnort);
        $EA_Starter->setStrasse($strasse);
        $EA_Starter->setStartzeit($startzeit);
        $EA_Starter->setKonfiguration($this->konfiguration);

        return $EA_Starter;
    }

    private function securityChecksTeilnehmer(EA_Starter $teilnehmer): bool
    {
        $error = false;
            //avoid double number
            if ($this->EA_StarterRepository->loadByStartnummer($teilnehmer->getStartnummer()) !== null) {
                $this->EA_Messages->addMessage("Diese Startnummer gibt es schon",1337534273,EA_Message::MESSAGE_ERROR);           
                $error = true;
            }
            //avoid double transponder
            if ($this->konfiguration->getTransponder() === EA_Configuration::TRANSPONDER_YES) {
                if ($this->EA_StarterRepository->loadByTransponder($teilnehmer->getTransponder()) !== null) {
                    $this->EA_Messages->addMessage("Dieser Transponder ist schon in Benutzung",1353747735,EA_Message::MESSAGE_ERROR);           
                    $error = true;
                }
            }
            //check for emty entries
            if($teilnehmer->getVorname() === ""){
                $this->EA_Messages->addMessage("Vorname nicht ausgefüllt",32131237477,EA_Message::MESSAGE_ERROR);       
                $error = true;    
            }
            if($teilnehmer->getName() === ""){
                $this->EA_Messages->addMessage("Nachname nicht ausgefüllt",35783534531,EA_Message::MESSAGE_ERROR);        
                $error = true;   
            }
            if($teilnehmer->getGeschlecht() === ""){
                $this->EA_Messages->addMessage("Geschlecht nicht ausgefüllt",35353543437,EA_Message::MESSAGE_ERROR);  
                $error = true;         
            }
            if($teilnehmer->getStrecke() === ""){
                $this->EA_Messages->addMessage("Strecke nicht ausgefüllt",12323737,EA_Message::MESSAGE_ERROR);   
                $error = true;        
            }
            //avoid double registration
            if ($this->EA_StarterRepository->loadByFilter(null,null,null,$teilnehmer->getVorname(),$teilnehmer->getName(),$teilnehmer->getGeburtsdatum()) !== null) {
                $this->EA_Messages->addMessage("Diese Anmeldung ist schon im System",3273235323745,EA_Message::MESSAGE_ERROR);           
                $error = true;
            }
        
        return $error;
    }

}