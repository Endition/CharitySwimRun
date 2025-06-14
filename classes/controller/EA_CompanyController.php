<?php
namespace CharitySwimRun\classes\controller;

use Doctrine\ORM\EntityManager;


use CharitySwimRun\classes\model\EA_Company;
use CharitySwimRun\classes\model\EA_Message;

class EA_CompanyController extends EA_Controller
{
    
    public function __construct( EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }
    
    public function getPageUnternehmene(): string
    {
        $content = "";
        $unternehmen = new EA_Company();

        if (isset($_POST['sendUnternehmenData'])) {
            $this->createAndUpdateUnternehmen();
        } elseif(isset($_GET['action']) && $_GET['action'] === "unternehmensfusion"){
            $this->fusionUnternehmen();
        }elseif (isset($_GET['action']) && $_GET['action'] === "edit") {
            $unternehmen = $this->editUnternehmen();
        } elseif (isset($_GET['action']) && $_GET['action'] === "delete") {
            $this->deleteUnternehmen();
        } else {
            $unternehmen = new EA_Company();
        }

        $content .= $this->getUnternehmenList();
        $content .= $this->EA_FR->getFormUnternehmen($unternehmen);
        $content .= $this->EA_FR->getFormUnternehmensfusion($this->EA_CompanyRepository->loadList("unternehmen"));
        return $content;
    }

    private function createAndUpdateUnternehmen(): void
    {
        $id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);
        $bezeichnung = htmlspecialchars($_POST['unternehmen']);
        
        //intinalize Object
        $unternehmen = ($id === null || $id === false || $id === "") ? new EA_Company() : $this->EA_CompanyRepository->loadById((int)$id);

        //checks for update und create case
        if($bezeichnung === ""){
            $this->EA_Messages->addMessage("Bezeichnung nicht ausgefüllt",134478656735,EA_Message::MESSAGE_ERROR);
            return;
        }

        //checks only for create case
        if($unternehmen->getId() === null){
            if($this->EA_CompanyRepository->isAvailable("unternehmen", $bezeichnung) === false){
                $this->EA_Messages->addMessage("Die Bezeichnung {$bezeichnung} für die Unternehmen ist schon vergeben",12646556765,EA_Message::MESSAGE_ERROR);
                return;
            }
        }
        
        //set properties
        $unternehmen->setUnternehmen($bezeichnung);
        
        //create case
        if($unternehmen->getId() === null){
            $this->EA_CompanyRepository->create($unternehmen);
            $this->EA_Messages->addMessage("Eintrag angelegt",12666557445,EA_Message::MESSAGE_SUCCESS);
        //update case
        }else{
            $this->EA_CompanyRepository->update();
            $this->EA_Messages->addMessage("Eintrag geändert",143376835454,EA_Message::MESSAGE_SUCCESS);
        }
        
    }

    private function fusionUnternehmen(): void
    {
        $ausgangUnternehmen = $this->EA_CompanyRepository->loadById(filter_input(INPUT_POST,'ausgangsunternehmen',FILTER_SANITIZE_NUMBER_INT));
        $zielUnternehmen = $this->EA_CompanyRepository->loadById(filter_input(INPUT_POST,'zielunternehmen',FILTER_SANITIZE_NUMBER_INT));
        
        if($ausgangUnternehmen === null || $zielUnternehmen === null){
            $this->EA_Messages->addMessage("Konnte Unternehmene nicht korrekt laden.",15656378213,EA_Message::MESSAGE_ERROR);
            return;
        }

        if($ausgangUnternehmen === $zielUnternehmen){
            $this->EA_Messages->addMessage("Zielunternehmen darf nicht gleich Ausgangsunternehmen sein.",12353723773,EA_Message::MESSAGE_ERROR);
            return;
        }

        if($this->EA_CompanyRepository->fusion($ausgangUnternehmen, $zielUnternehmen)){
            $this->EA_CompanyRepository->delete($ausgangUnternehmen);
            $this->EA_Messages->addMessage("Unternehmen {$ausgangUnternehmen->getUnternehmen()} -> {$zielUnternehmen->getUnternehmen()}  fusioniert. {$ausgangUnternehmen->getUnternehmen()} gelöscht.",13573773447,EA_Message::MESSAGE_SUCCESS);
        }
         
    }

    private function editUnternehmen(): ?EA_Company
    {
        $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        $unternehmen = $this->EA_CompanyRepository->loadById($id);
        if($unternehmen === null){
            $this->EA_Messages->addMessage("Keine Unternehmen gefunden.",156567875,EA_Message::MESSAGE_WARNING);
        }
        return $unternehmen;
    }

    private function getUnternehmenList():  string
    {
        $content = "";
        $unternehmenList = $this->EA_CompanyRepository->loadList();
        if ($unternehmenList !== []) {
            $content = $this->EA_R->renderTabelleUnternehmen($unternehmenList);
        } else {
            $this->EA_Messages->addMessage("Es sind noch keine Unternehmenen angelegt.",155677878,EA_Message::MESSAGE_WARNING);
        } 
        return $content;
    }

    private function deleteUnternehmen(): void
    {
        $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        $unternehmen = $this->EA_CompanyRepository->loadById($id);

        if($unternehmen === null){
            $this->EA_Messages->addMessage("Kein Unternehmen gefunden.",174567814,EA_Message::MESSAGE_ERROR);
            return;
        }
        if($this->EA_CompanyRepository->isInUse($unternehmen) === true){
            $this->EA_Messages->addMessage("Unternehmen ist noch in Benutzung.",17646546577,EA_Message::MESSAGE_ERROR);
            return;
        }

        $this->EA_CompanyRepository->delete($unternehmen);
        $this->EA_Messages->addMessage("Unternehmen gelöscht.",17655645678,EA_Message::MESSAGE_SUCCESS);
    }
}