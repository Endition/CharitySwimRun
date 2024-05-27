<?php
namespace CharitySwimRun\classes\controller;

use Doctrine\ORM\EntityManager;

use CharitySwimRun\classes\model\EA_Hit;
use CharitySwimRun\classes\model\EA_Starter;
use CharitySwimRun\classes\model\EA_Message;
use CharitySwimRun\classes\helper\EA_StatistikHelper;
use CharitySwimRun\classes\model\EA_Repository;

class EA_StarterHitOverviewController extends EA_Controller
{
    private EA_StatistikHelper $EA_StatistikHelper;

    public function __construct(EA_Repository $EA_Repository)
    {
        parent::__construct($EA_Repository->getEntityManager());
        $this->EA_StatistikHelper = new EA_StatistikHelper($EA_Repository,$this->EA_ConfigurationRepository->load());

    }

    public function getPageBuchungenStarter(): string
    {
        $messages = [];
        $content = "";
        $content .= $this->EA_FR->getFormSelectTeilnehmer();
        
        if (isset($_GET['action']) && $_GET['action'] === "delete" && isset($_GET['impulsid'])) {
            $this->deleteImpuls();
        }
        if (isset($_GET['action']) && $_GET['action'] === "deleteall" && isset($_GET['teilnehmerid'])) {
            $this->deleteAllImpuls();
        }
        if (isset($_GET['action']) && $_GET['action'] === "add" && isset($_GET['teilnehmerid'])) {
            $this->addImpuls();
        }
        if (((isset($_POST['TeilnehmerSuchen']) && isset($_POST['id'])) || isset($_GET['teilnehmerid']))) {
            $content .= $this->showBuchungenStarter();
        }

        return $content;
    }

    private function showBuchungenStarter(): string
    {
        $idPost = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);
        $idGet = filter_input(INPUT_GET,'teilnehmerid',FILTER_SANITIZE_NUMBER_INT);
        $suchText = htmlspecialchars($_POST['TeilnehmerSuchen']);  
        $id = null;
        $teilnehmer = null;
        if(is_numeric($idPost) && $idPost > 0){
            $id = (int)$idPost;
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
            $this->EA_Messages->addMessage("Keine Teilnehmer gefunden.",156567875,EA_Message::MESSAGE_WARNINIG);
            return "";
        }

        return $this->EA_FR->getFormBuchungenStarter($this->entityManager, $teilnehmer, $teilnehmer->getImpulseListGueltige(true), $this->EA_StatistikHelper->loadStatistikData("BuchungenProStunde", $teilnehmer->getId()));
    }

    private function addImpuls(): void
    {
        // $_POST['sendImpulseEinlaufenData'] beinhaltet die TN-ID
        $EA_Starter = $this->EA_StarterRepository->loadById(filter_input(INPUT_GET, "teilnehmerid", FILTER_SANITIZE_NUMBER_INT));
        if($EA_Starter === null){
            $this->EA_Messages->addMessage("Der Teilnehmer konnte nicht gefunden werden.",1231323511,EA_Message::MESSAGE_SUCCESS);
            return;
        }
        $impuls = $this->initiateImpuls($EA_Starter);
        $this->EA_HitRepository->create($impuls);
        $this->EA_StarterRepository->resetPlaetzeTeilnehmer($EA_Starter);
        $this->EA_Messages->addMessage("Impuls für TN-ID " . $impuls->getTeilnehmer()->getId() . " - um " . $impuls->getTimestamp("d.m.Y H:i:s") . " gespeichert",17322443535,EA_Message::MESSAGE_SUCCESS);

    }

    private function deleteImpuls(): void
    {
        $impuls = $this->EA_HitRepository->loadById(filter_input(INPUT_GET, "impulsid", FILTER_SANITIZE_NUMBER_INT));
        $this->EA_HitRepository->delete($impuls);
        $this->EA_StarterRepository->resetPlaetzeTeilnehmer($impuls->getTeilnehmer());
        $this->EA_Messages->addMessage("Impuls für TN-ID " . $impuls->getTeilnehmer()->getId() . " - um " . $impuls->getTimestamp("d.m.Y H:i:s") . " gelöscht",17322443535,EA_Message::MESSAGE_SUCCESS);
    }

    private function deleteAllImpuls(): void
    {
        $EA_Starter = $this->EA_StarterRepository->loadById(filter_input(INPUT_GET, "teilnehmerid", FILTER_SANITIZE_NUMBER_INT));
        $this->EA_HitRepository->deleteAllByTeilnehmer($EA_Starter);
        $this->EA_StarterRepository->resetPlaetzeTeilnehmer($EA_Starter );
        $this->EA_Messages->addMessage("Alle Buchungen für Teilnehmer " . $EA_Starter->getGesamtname() . " gelöscht",15632124455,EA_Message::MESSAGE_SUCCESS);
    }

    private function initiateImpuls(EA_Starter $EA_Starter): EA_Hit
    {
        $impuls = new EA_Hit();
        $impuls->setTeilnehmer($EA_Starter);
        $impuls->setTransponderId($EA_Starter->getTransponder());
        $impuls->setTimestamp(time());
        $impuls->setLeser(99);
        
        return $impuls;
    }

}