<?php
namespace CharitySwimRun\classes\controller;

use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use CharitySwimRun\classes\model\EA_SpecialEvaluation;
use CharitySwimRun\classes\model\EA_Message;



class EA_SpecialEvaluationController extends EA_Controller
{    
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function getPageSpecialEvaluationn(): string
    {
        $content = "";
        $specialEvaluation = new EA_SpecialEvaluation();

        if (isset($_POST['sendSpecialEvaluationData'])) {
            $this->createAndUpdateSpecialEvaluation();
        } elseif (isset($_GET['action']) && $_GET['action'] === "edit") {
            $specialEvaluation = $this->editSpecialEvaluation();
        } elseif (isset($_GET['action']) && $_GET['action'] === "delete") {
            $this->deleteSpecialEvaluation();
        } else {
            $specialEvaluation = new EA_SpecialEvaluation();
        }

        $content .= $this->getSpecialEvaluationList();
        $content .= $this->EA_FR->getFormSpecialEvaluation($this->entityManager, $specialEvaluation);
        return $content;
    }

    private function createAndUpdateSpecialEvaluation(): void
    {
        $id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);
        $name = htmlspecialchars($_POST['name']);
        $start = htmlspecialchars($_POST['start']);
        $ende = htmlspecialchars($_POST['end']);
        $geschlecht = htmlspecialchars($_POST['geschlecht']);
        $altersklasseId = htmlspecialchars($_POST['altersklasse']);
        $streckeId = htmlspecialchars($_POST['strecke']);
        $leser = htmlspecialchars($_POST['leser']);
        $strecke = null;
        $altersklasse = null;
        $ende = new DateTimeImmutable($ende);
        $start = new DateTimeImmutable($start);
        //intinalize Object
        $specialEvaluation = ($id === null || $id === false || $id === "") ? new EA_SpecialEvaluation() : $this->EA_SpecialEvaluationRepository->loadById((int)$id);

        //checks for update und create case
        if($name === ""){
            $this->EA_Messages->addMessage("Name nicht ausgefüllt",1232376737,EA_Message::MESSAGE_ERROR);
            return;
        }

        if($ende < $start){
            $this->EA_Messages->addMessage("Start ist größer als Ende",135534657,EA_Message::MESSAGE_ERROR);
            return;
        }


        //checks only for create case
        if($specialEvaluation->getId() === null){
            if($this->EA_SpecialEvaluationRepository->isAvailable("name", $name) === false){
                $this->EA_Messages->addMessage("Die Bezeichnung {$name} für die SpecialEvaluation ist schon vergeben",335543456,EA_Message::MESSAGE_ERROR);
                return;
            }
        }
        
        if(is_numeric($streckeId)){
            $strecke = $this->EA_DistanceRepository->loadById($streckeId);
        }
        if(is_numeric($altersklasseId)){
            $altersklasse = $this->EA_AgeGroupRepository->loadById($altersklasseId);
        }


        //set properties
        $specialEvaluation->setName($name);
        $specialEvaluation->setStart($start);
        $specialEvaluation->setEnd($ende);
        $specialEvaluation->setStrecke($strecke);
        $specialEvaluation->setAltersklasse($altersklasse);
        $specialEvaluation->setGeschlecht($geschlecht === "" ? null : $geschlecht);
        $specialEvaluation->setLeser($leser === "" ? null : $leser);

        //create case
        if($specialEvaluation->getId() === null){
            $this->EA_SpecialEvaluationRepository->create($specialEvaluation);
            $this->EA_Messages->addMessage("Eintrag angelegt",323423877,EA_Message::MESSAGE_SUCCESS);
        //update case
        }else{
            $this->EA_SpecialEvaluationRepository->update();
            $this->EA_Messages->addMessage("Eintrag geändert",323437821,EA_Message::MESSAGE_SUCCESS);
        }
        
    }

    private function editSpecialEvaluation(): ?EA_SpecialEvaluation
    {
        $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        $specialEvaluation = $this->EA_SpecialEvaluationRepository->loadById($id);
        if($specialEvaluation === null){
            $this->EA_Messages->addMessage("Keine SpecialEvaluation gefunden.",373465645345,EA_Message::MESSAGE_WARNINIG);
        }
        return $specialEvaluation;
    }

    private function getSpecialEvaluationList():  string
    {
        $content = "";
        $specialEvaluationList = $this->EA_SpecialEvaluationRepository->loadList();
        if ($specialEvaluationList !== []) {
            $content = $this->EA_R->renderTabelleSpecialEvaluation($specialEvaluationList);
        } else {
            $this->EA_Messages->addMessage("Es sind noch keine SpecialEvaluationen angelegt.",3534567674,EA_Message::MESSAGE_WARNINIG);
        } 
        return $content;
    }

    private function deleteSpecialEvaluation(): void
    {
        $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        $specialEvaluation = $this->EA_SpecialEvaluationRepository->loadById($id);

        if($specialEvaluation === null){
            $this->EA_Messages->addMessage("Keine SpecialEvaluation gefunden.",335453431213,EA_Message::MESSAGE_ERROR);
            return;
        }

        $this->EA_SpecialEvaluationRepository->delete($specialEvaluation);
        $this->EA_Messages->addMessage("SpecialEvaluation gelöscht.",3534537236,EA_Message::MESSAGE_SUCCESS);
    }
}