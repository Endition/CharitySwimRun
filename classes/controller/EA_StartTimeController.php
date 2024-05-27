<?php
namespace CharitySwimRun\classes\controller;

use Doctrine\ORM\EntityManager;

use CharitySwimRun\classes\model\EA_Starter;
use CharitySwimRun\classes\model\EA_Message;


class EA_StartTimeController  extends EA_Controller
{
    public function __construct( EntityManager $entityManager)
    {
        parent::__construct($entityManager);


    }

    public function getPageStartzeiten(): string
    {
        $content = "";
        $geschlecht = null;
        $spalte = null;
        $this->EA_StarterRepository->berechneStati();
        if (isset($_GET['action']) && ($_GET['action'] === "start" || $_GET['action'] === "editstartzeit")) {
            if (isset($_GET['action']) && $_GET['action'] === "start") {
                $startzeit = new \DateTime();
                $uberschreiben = false;
            } elseif (isset($_GET['action']) && $_GET['action'] === "editstartzeit") {
                $startzeit = \DateTime::createFromFormat('d.m.Y H:i:s', $_POST['datum'] . " " . $_POST['zeit']);
                $uberschreiben = true;
            }
            if ($_GET['typ'] === "strecke") {
                $spalte = "Strecke";
                $spalteValue = !is_array($_POST['strecke']) ? $_POST['strecke'] : null;
                $spalteArray = is_array($_POST['strecke']) ? $_POST['strecke'] : null;
                $geschlecht = null;
            } elseif ($_GET['typ'] === "altersklasse") {
                $spalte = "Altersklasse";
                $aufsplitten = explode("#", $_POST['altersklasse']);
                $spalteValue = $aufsplitten[0];
                $geschlecht = ($aufsplitten[1] != 'MW') ? $aufsplitten[1] : null;
                $spalteArray = null;
            } elseif ($_GET['typ'] === "teilnehmer") {
                $spalte = "Id";
                $spalteValue = !is_array($_POST['teilnehmer']) ? $_POST['teilnehmer'] : null;
                $spalteArray = is_array($_POST['teilnehmer']) ? $_POST['teilnehmer'] : null;
                $geschlecht = null;
            } elseif ($_GET['typ'] === "startgruppe") {
                $spalte = "Startgruppe";
                $spalteValue = !is_array($_POST['startgruppe']) ? $_POST['startgruppe'] : null;
                $spalteArray = is_array($_POST['startgruppe']) ? $_POST['startgruppe'] : null;
                $geschlecht = null;
            }
            $this->EA_StarterRepository->updateStatus(EA_Starter::STATUS_GESTARTET,$geschlecht,$spalte,$spalteValue,$spalteArray);
            $answer = $this->EA_StarterRepository->updateStartzeit($startzeit,$uberschreiben,$geschlecht,$spalte,$spalteValue,$spalteArray);
            if ($answer > 0) {
                $this->EA_Messages->addMessage($answer . " Teilnehmer gestartet mit der Zeit " . $startzeit->format('d.m.Y H:i:s'),174635735,EA_Message::MESSAGE_SUCCESS);            
            } else {
                $this->EA_Messages->addMessage($answer . " Teilnehmer gestartet",193534574,EA_Message::MESSAGE_SUCCESS);            
            }
        }
        $content .= $this->EA_FR->getFormStartzeiten($this->entityManager, $this->EA_StarterRepository->loadList(null,null,null,null,null,null,null,"startnummer","DESC", false), $this->EA_StarterRepository->loadList(null,null,null,null,null,null,null,"startnummer","DESC", true));
        return $content;
    }


}