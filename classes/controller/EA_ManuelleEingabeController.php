<?php
namespace CharitySwimRun\classes\controller;

use Doctrine\ORM\EntityManager;

use CharitySwimRun\classes\model\EA_Impuls;
use CharitySwimRun\classes\model\EA_Teilnehmer;
use CharitySwimRun\classes\model\EA_Message;



class EA_ManuelleEingabeController extends EA_Controller
{
    public function __construct( EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function getPageManuelleEingabe(): string
    {
        if (isset($_POST['sendImpulseRundenData'])) {
            $startnummer = (isset($_POST['startnummer'])) ? filter_input(INPUT_POST, "startnummer",FILTER_SANITIZE_NUMBER_INT) : null;
            $anzahlimpulse = (isset($_POST['anzahlimpulse'])) ? filter_input(INPUT_POST, "anzahlimpulse",FILTER_SANITIZE_NUMBER_INT) : null;
            $impuls = null;
            
            $EA_T = $this->EA_TeilnehmerRepository->loadByStartnummer($startnummer);
            if($EA_T === null){
                $this->EA_Messages->addMessage("Kein Teilnehmer gefunden",133553548936,EA_Message::MESSAGE_ERROR);
            }else{
                $impuls = $this->initiateImpuls($EA_T);
            }

            if ($impuls !== null && ($anzahlimpulse > 0 || $anzahlimpulse < 150)) {
                for ($i = 1; $i <= $anzahlimpulse; $i++) {
                    //keep origin entity clean. If we hand over $impulse no multiple entities will be created
                    $impuls2 = clone $impuls;
                    $this->EA_ImpulsRepository->create($impuls2);
                }
                $this->EA_Messages->addMessage($anzahlimpulse . " Impulse für TN-ID " . $impuls->getTeilnehmer()->getId() . " - um " . $impuls->getTimestamp("d.m.Y H:i:s") . " gespeichert",133553548936,EA_Message::MESSAGE_SUCCESS);
            } else {
                $this->EA_Messages->addMessage("Keine Anzahl oder eine Zahl größer 150 eingegeben",182337742235,EA_Message::MESSAGE_ERROR);
            }
        }
        $content = $this->EA_FR->getFormManuelleEingabe($this->entityManager, $this->EA_StreckeRepository->getListForSelectField());
        return $content;
    }

    private function initiateImpuls(EA_Teilnehmer $EA_Teilnehmer): EA_Impuls
    {
        $impuls = new EA_Impuls();
        $impuls->setTeilnehmer($EA_Teilnehmer);
        $impuls->setTransponderId($EA_Teilnehmer->getTransponder());
        $impuls->setTimestamp(time());
        $impuls->setLeser(99);
        
        return $impuls;
    }

}