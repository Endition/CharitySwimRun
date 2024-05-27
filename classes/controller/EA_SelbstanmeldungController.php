<?php
namespace EndeAuswertung\classes\controller;

use DateTime;
use Doctrine\ORM\EntityManager;


use EndeAuswertung\classes\model\EA_Teilnehmer;
use EndeAuswertung\classes\model\EA_Verein;
use EndeAuswertung\classes\model\EA_Message;

class EA_SelbstanmeldungController extends EA_Controller
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
            $EA_T = $this->initiateTeilnehmer();
            $error = $this->securityChecksTeilnehmer($messages, $EA_T);

            if ($error === false) {
                $this->EA_TeilnehmerRepository->create($EA_T);
                $content .= $this->EA_FR->getInfoSelbstanmeldung($this->entityManager, $EA_T);
                $EA_T = new EA_Teilnehmer();
            }
        }else{
            $content .= $this->EA_FR->getFormSelbstanmeldung($this->entityManager, $this->konfiguration);
        }
        $content = $this->EA_Messages->renderMessageAlertList() . " " . $content;

        return $content;
    }

    
    private function initiateTeilnehmer(): ?EA_Teilnehmer
    {
        $EA_T = new EA_Teilnehmer();

        $startnummer = rand(9000,10000);
        $transponder = $startnummer;
        $name = htmlspecialchars($_POST['name']);
        $vorname = htmlspecialchars($_POST['vorname']);
        $geburtsdatum = (isset($_POST['geburtsdatum'])) ? new \DateTimeImmutable(htmlspecialchars($_POST['geburtsdatum'])) : null;
        $geschlecht = htmlspecialchars($_POST['geschlecht']);
        $mannschaft = (isset($_POST['mannschaft']) && $_POST['mannschaft'] > 0) ? $this->EA_MannschaftRepository->loadById(htmlspecialchars($_POST['mannschaft'])) : null;
        if (isset($_POST['verein']) && $_POST['verein'] !== "") {
            $verein = $this->EA_VereinRepository->loadByBezeichnung(htmlspecialchars($_POST['verein']));
            if($verein === null){
                $verein = new EA_Verein();
                $verein->setVerein(htmlspecialchars($_POST['verein']));
                $this->EA_VereinRepository->create($verein);
            }
        } elseif (isset($_POST['vereinid']) && is_numeric($_POST['vereinid']) && $_POST['vereinid'] > 0) {
            $verein = $this->EA_VereinRepository->loadById(htmlspecialchars($_POST['vereinid']));
        } else {
            $verein = null;
        }
        $strecke = (isset($_POST['strecke'])) ? $this->EA_StreckeRepository->loadById($_POST['strecke']) :null;
        $startgruppe = (isset($_POST['startgruppe'])) ? htmlspecialchars($_POST['startgruppe']) : null;
        $mail = (isset($_POST['mail'])) ? htmlspecialchars($_POST['mail']) : "";
        $plz = (isset($_POST['plz'])) ? htmlspecialchars($_POST['plz']) : null;
        $wohnort = (isset($_POST['wohnort'])) ? htmlspecialchars($_POST['wohnort']) : "";
        $strasse = (isset($_POST['strasse'])) ? htmlspecialchars($_POST['strasse']) : "";
        $startzeit = ($this->konfiguration->getStarttyp() === "aba") ? new \DateTimeImmutable() : null;

        $EA_T->setStartnummer($startnummer);
        $EA_T->setTransponder($transponder);
        $EA_T->setName($name);
        $EA_T->setVorname($vorname);
        $EA_T->setGeburtsdatum($geburtsdatum);

        if ($this->konfiguration->getAltersklassen() === 1) {
            $altersklasse = $this->EA_AltersklasseRepository->findByAlter($geburtsdatum, $this->konfiguration->getEnde());
        } else {
            $altersklasse = $this->EA_AltersklasseRepository->findByGeburtsjahr($EA_T->getGeburtsdatum());
        }
        $EA_T->setAltersklasse($altersklasse);
        $EA_T->setGeschlecht($geschlecht);
        $EA_T->setMannschaft($mannschaft);
        $EA_T->setVerein($verein);
        $EA_T->setStrecke($strecke);
        $EA_T->setStartgruppe($startgruppe);
        $EA_T->setMail($mail);
        $EA_T->setPlz($plz);
        $EA_T->setWohnort($wohnort);
        $EA_T->setStrasse($strasse);
        $EA_T->setStartzeit($startzeit);
        $EA_T->setKonfiguration($this->konfiguration);


        return $EA_T;
    }

    private function securityChecksTeilnehmer(&$messages, EA_Teilnehmer $teilnehmer): bool
    {
        $error = false;
            //avoid double number
            if ($this->EA_TeilnehmerRepository->loadByStartnummer($teilnehmer->getStartnummer()) !== null) {
                $this->EA_Messages->addMessage("Diese Startnummer gibt es schon",1337534273,EA_Message::MESSAGE_ERROR);           
                $error = true;
            }
            //avoid double transponder
            if ($this->EA_TeilnehmerRepository->loadByTransponder($teilnehmer->getTransponder()) !== null) {
                $this->EA_Messages->addMessage("Dieser Transponder ist schon in Benutzung",1353747735,EA_Message::MESSAGE_ERROR);           
                $error = true;
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
            if ($this->EA_TeilnehmerRepository->loadByFilter(null,null,null,$teilnehmer->getVorname(),$teilnehmer->getName(),$teilnehmer->getGeburtsdatum()) !== null) {
                $this->EA_Messages->addMessage("Diese Anmeldung ist schon im System",3273235323745,EA_Message::MESSAGE_ERROR);           
                $error = true;
            }
        
        return $error;
    }

}