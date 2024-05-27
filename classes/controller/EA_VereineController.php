<?php
namespace CharitySwimRun\classes\controller;

use Doctrine\ORM\EntityManager;


use CharitySwimRun\classes\model\EA_Verein;
use CharitySwimRun\classes\model\EA_Message;

class EA_VereineController  extends EA_Controller
{
    
    public function __construct( EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }
    
    public function getPageVereine(): string
    {
        $content = "";
        $verein = new EA_Verein();

        if (isset($_POST['sendVereinData'])) {
            $this->createAndUpdateVerein();
        } elseif(isset($_GET['action']) && $_GET['action'] === "vereinsfusion"){
            $this->fusionVerein();
        }elseif (isset($_GET['action']) && $_GET['action'] === "edit") {
            $verein = $this->editVerein();
        } elseif (isset($_GET['action']) && $_GET['action'] === "delete") {
            $this->deleteVerein();
        } else {
            $verein = new EA_Verein();
        }

        $content .= $this->getVereinList();
        $content .= $this->EA_FR->getFormVerein($verein);
        $content .= $this->EA_FR->getFormVereinsfusion($this->EA_VereinRepository->loadList());
        return $content;
    }

    private function createAndUpdateVerein(): void
    {
        $id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);
        $bezeichnung = htmlspecialchars($_POST['verein']);
        
        //intinalize Object
        $verein = ($id === null || $id === false || $id === "") ? new EA_Verein() : $this->EA_VereinRepository->loadById((int)$id);

        //checks for update und create case
        if($bezeichnung === ""){
            $this->EA_Messages->addMessage("Bezeichnung nicht ausgefüllt",134478656735,EA_Message::MESSAGE_ERROR);
            return;
        }

        //checks only for create case
        if($verein->getId() === null){
            if($this->EA_VereinRepository->isAvailable("verein", $bezeichnung) === false){
                $this->EA_Messages->addMessage("Die Bezeichnung {$bezeichnung} für die Verein ist schon vergeben",12646556765,EA_Message::MESSAGE_ERROR);
                return;
            }
        }
        
        //set properties
        $verein->setVerein($bezeichnung);
        
        //create case
        if($verein->getId() === null){
            $this->EA_VereinRepository->create($verein);
            $this->EA_Messages->addMessage("Eintrag angelegt",12666557445,EA_Message::MESSAGE_SUCCESS);
        //update case
        }else{
            $this->EA_VereinRepository->update();
            $this->EA_Messages->addMessage("Eintrag geändert",143376835454,EA_Message::MESSAGE_SUCCESS);
        }
        
    }

    private function fusionVerein(): void
    {
        $ausgangVerein = $this->EA_VereinRepository->loadById(filter_input(INPUT_POST,'ausgangsverein',FILTER_SANITIZE_NUMBER_INT));
        $zielVerein = $this->EA_VereinRepository->loadById(filter_input(INPUT_POST,'zielverein',FILTER_SANITIZE_NUMBER_INT));
        
        if($ausgangVerein === null || $zielVerein === null){
            $this->EA_Messages->addMessage("Konnte Vereine nicht korrekt laden.",15656378213,EA_Message::MESSAGE_ERROR);
            return;
        }

        if($this->EA_VereinRepository->fusion($ausgangVerein, $zielVerein)){
            $this->EA_VereinRepository->delete($ausgangVerein);
            $this->EA_Messages->addMessage("Verein {$ausgangVerein->getVerein()} -> {$zielVerein->getVerein()}  fusioniert. {$ausgangVerein->getVerein()} gelöscht.",13573773447,EA_Message::MESSAGE_SUCCESS);
        }
         
    }

    private function editVerein(): ?EA_Verein
    {
        $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        $verein = $this->EA_VereinRepository->loadById($id);
        if($verein === null){
            $this->EA_Messages->addMessage("Keine Verein gefunden.",156567875,EA_Message::MESSAGE_WARNINIG);
        }
        return $verein;
    }

    private function getVereinList():  string
    {
        $content = "";
        $vereinList = $this->EA_VereinRepository->loadList();
        if ($vereinList !== []) {
            $content = $this->EA_R->renderTabelleVereine($vereinList);
        } else {
            $this->EA_Messages->addMessage("Es sind noch keine Vereinen angelegt.",155677878,EA_Message::MESSAGE_WARNINIG);
        } 
        return $content;
    }

    private function deleteVerein(): void
    {
        $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        $verein = $this->EA_VereinRepository->loadById($id);

        if($verein === null){
            $this->EA_Messages->addMessage("Kein Verein gefunden.",174567814,EA_Message::MESSAGE_ERROR);
            return;
        }
        if($this->EA_VereinRepository->isInUse($verein) === true){
            $this->EA_Messages->addMessage("Verein ist noch in Benutzung.",17646546577,EA_Message::MESSAGE_ERROR);
            return;
        }

        $this->EA_VereinRepository->delete($verein);
        $this->EA_Messages->addMessage("Verein gelöscht.",17655645678,EA_Message::MESSAGE_SUCCESS);
    }
}