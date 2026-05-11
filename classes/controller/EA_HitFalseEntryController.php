<?php
namespace CharitySwimRun\classes\controller;

use Doctrine\ORM\EntityManager;

use CharitySwimRun\classes\model\EA_Message;

class EA_HitFalseEntryController extends EA_Controller
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
        $content .= $this->EA_FR->getFormFehlbuchungen($this->EA_HitRepository->loadFehlbuchungen());
        return $content;
    }

    private function zuordneFehlbuchung(): void
    {
        if (!isset($_POST['tpaction']) || !is_array($_POST['tpaction'])) {
            return;
        }

        foreach ($_POST['tpaction'] as $impulsId => $checkboxValue) {
            $impulsId = (int)$impulsId;
            if (isset($_POST['tpzuordnenTnId'][$impulsId])) {
                $startnummer = trim((string)$_POST['tpzuordnenTnId'][$impulsId]);
                
                if ($startnummer !== "") {
                    $impuls = $this->EA_HitRepository->loadById($impulsId);
                    if (!$impuls) {
                        $this->EA_Messages->addMessage("Impuls ID {$impulsId} nicht gefunden.", 0, EA_Message::MESSAGE_ERROR);
                        continue;
                    }

                    $teilnehmer = $this->EA_StarterRepository->loadByStartnummer((int)$startnummer);
                    
                    if ($teilnehmer === null) {
                        $this->EA_Messages->addMessage("Startnummer '{$startnummer}' wurde nicht im System gefunden.", 156573535744, EA_Message::MESSAGE_ERROR);
                    } else {
                        $impuls->setTeilnehmer($teilnehmer);
                        $impuls->setTransponderId(null);
                        $this->EA_HitRepository->update();
                        $this->EA_Messages->addMessage("Impuls {$impulsId} wurde erfolgreich Teilnehmer {$teilnehmer->getGesamtname()} (StNr: {$teilnehmer->getStartnummer()}) zugeordnet.", 32647777141, EA_Message::MESSAGE_SUCCESS);
                    }
                } else {
                    $this->EA_Messages->addMessage("Keine Startnummer für Impuls {$impulsId} eingegeben.", 0, EA_Message::MESSAGE_WARNING);
                }
            }
        }
    }

    private function deleteFehlbuchung(): void
    {
        if (!isset($_POST['tpaction']) || !is_array($_POST['tpaction'])) {
            return;
        }

        foreach ($_POST['tpaction'] as $impulsId => $value) {
            if ($value === "true" && ctype_digit((string)$impulsId)) {
                $impuls = $this->EA_HitRepository->loadById($impulsId);
                if ($impuls) {
                    $impuls->setGeloescht(true);
                    if ($this->EA_HitRepository->update()) {
                        $this->EA_Messages->addMessage("Fehlbuchung {$impulsId} als gelöscht markiert", 1325375411, EA_Message::MESSAGE_SUCCESS);
                    }
                }
            }
        }
    }

}