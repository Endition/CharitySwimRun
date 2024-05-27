<?php
namespace CharitySwimRun\classes\controller;

use DateTimeImmutable;
use Doctrine\ORM\EntityManager;

use CharitySwimRun\classes\model\EA_Configuration;
use CharitySwimRun\classes\model\EA_Message;

class EA_ConfigurationController extends EA_Controller
{

    public function __construct( EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }
    
    public function getPageKonfiguration(): string
    {
        $konfiguration = $this->EA_ConfigurationRepository->load();
        if ($konfiguration === null) {
            $konfiguration = new EA_Configuration();
        }
            
        if (isset($_POST['sendKonfiguration'])) {
            $konfiguration = $this->saveKonfiguration();
            $this->EA_Messages->addMessage("Einstellungen gespeichert",1256897777,EA_Message::MESSAGE_SUCCESS);
        }
        return $this->EA_FR->getFormKonfiguration($konfiguration->getKonfiguration());
    }

    private function saveKonfiguration(): EA_Configuration
    {
        $konfiguration = $this->EA_ConfigurationRepository->load();
        if ($konfiguration === null) {
            $konfiguration = new EA_Configuration();
            $this->EA_ConfigurationRepository->create($konfiguration);
        }
        $konfiguration->setVeranstaltungsname($_POST['veranstaltungsname']);
        $konfiguration->setVeranstaltungslogo($_POST['veranstaltungslogo']);
        $konfiguration->setTransponder((bool)$_POST['transponder']);
        $konfiguration->setStarttyp($_POST['starttyp']);
        $konfiguration->setStreckenart($_POST['streckenart']);
        $konfiguration->setStartgruppen((bool)$_POST['startgruppen']);
        $konfiguration->setAltersklassen($_POST['altersklassen']);
        $konfiguration->setEnde(new DateTimeImmutable($_POST['ende']));
        $konfiguration->setStart(new DateTimeImmutable($_POST['start']));
        $konfiguration->setStartgruppenAnzahl($_POST['anzahl_startgruppen']);
        $konfiguration->setMannschaften((bool)$_POST['mannschaften']);
        $konfiguration->setMannschaftMitgliedAnzahl((int)$_POST['max_mitglieder']);
        $konfiguration->setMannschaftPunkteBerechnen($_POST['mannschaft_punkte_berechnung']);
        $konfiguration->setBeguenstigter($_POST['beguenstigter']);
        $konfiguration->setFaktor((int)$_POST['faktor']);
        $konfiguration->setRundenlaenge((float)$_POST['rundenlaenge']);
        $konfiguration->setEuroprometer((float)$_POST['europrometer']);
        $konfiguration->setGeld((float)$_POST['geld']);
        $konfiguration->setVeranstaltungsrekord((int)$_POST['veranstaltungsrekord']);
        $konfiguration->setTeilnehmerrekord((int)$_POST['teilnehmerrekord']);
        $konfiguration->setInputEmail((bool)$_POST['input_adresse']);
        $konfiguration->setInputAdresse((bool)$_POST['input_email']);
        $konfiguration->setSponsor($_POST['sponsor']);

        $this->EA_ConfigurationRepository->update();
        return $konfiguration;
    }
    
}