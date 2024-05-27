<?php
namespace CharitySwimRun\classes\controller;

use Doctrine\ORM\EntityManager;

use CharitySwimRun\classes\model\EA_Message;

class EA_FehlbuchungenController extends EA_Controller
{
    public function __construct( EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function getPageFehlbuchungen(): string
    {
        $messages = [];
        $content = "";
        if (isset($_POST['sendFehlbuchungenData']) && $_POST['tpactionselect'] === "zuordnen") {
            $this->zuordneFehlbuchung();
        } elseif (isset($_POST['sendFehlbuchungenData']) && $_POST['tpactionselect'] === "delete") {
            $this->deleteFehlbuchung();
        }
        $content .= $this->EA_FR->getFormFehlbuchungen($this->EA_ImpulsRepository->loadFehlbuchungen());
        return $content;
    }

    private function zuordneFehlbuchung():void
    {
        foreach ($_POST['tpzuordnenTnId'] as $impulsId => $startnummer) {
            if ($_POST['tpaction'][$impulsId] == "true" && $startnummer != null) {
                $impuls = $this->EA_ImpulsRepository->loadById($impulsId);
                $teilnehmer = $this->EA_StarterRepository->loadByStartnummer($startnummer);
                if ($teilnehmer === null) {
                    $this->EA_Messages->addMessage("Startnummer nicht gefunden",156573535744,EA_Message::MESSAGE_ERROR);
                } else {
                    $impuls->setTeilnehmer($teilnehmer);
                    $impuls->setTransponderId($teilnehmer->getTransponder());
                    $this->EA_ImpulsRepository->update();
                    $this->EA_Messages->addMessage("Fehlbuchung zugeordnet",32647777141,EA_Message::MESSAGE_SUCCESS);
                }
            }
        }
    }

    private function deleteFehlbuchung():void
    {
        foreach ($_POST['tpaction'] as $impulsId => $value) {
            if ($value == "true" && ctype_digit($impulsId)) {
                $impuls = $this->EA_ImpulsRepository->loadById($impulsId);
                $impuls->setGeloescht(true);
                if ($this->EA_ImpulsRepository->update()) {
                    $this->EA_Messages->addMessage("Fehlbuchung als gelöscht markiert",1325375411,EA_Message::MESSAGE_SUCCESS);
                }
            }
        }
    }

}