<?php
namespace CharitySwimRun\classes\controller;

use Doctrine\ORM\EntityManager;
use CharitySwimRun\classes\model\EA_CertificateElement;
use CharitySwimRun\classes\model\EA_Message;

class EA_CertificateGeneratorController  extends EA_Controller
{
    public function __construct( EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }

    //##################################################################
    public function getPageUrkundengenerator(): string
    {
        $content = "";
        $urkundenelement = new EA_CertificateElement();

        if (isset($_POST['sendUrkundenelementData'])) {
            $this->createAndUpdateUrkundenelement();
        } elseif (isset($_GET['action']) && $_GET['action'] === "edit") {
            $urkundenelement = $this->editUrkundenelement();
        } elseif (isset($_GET['action']) && $_GET['action'] === "delete") {
            $this->deleteUrkundenelement();
        } else {
            $urkundenelement = new EA_CertificateElement();
        }

        $content .= $this->getUrkundenelementList();
        $content .= $this->EA_FR->getFormUrkundenelement($urkundenelement);
        $content .= $this->EA_R->renderUrkundengeneratorJavascript($this->EA_CertificateElementRepository->loadList());
        return $content;
    }

    private function createAndUpdateUrkundenelement(): void
    {
        $id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);
        $x_wert = filter_input(INPUT_POST,'x_wert',FILTER_SANITIZE_NUMBER_INT); 
        $y_wert = filter_input(INPUT_POST,'y_wert',FILTER_SANITIZE_NUMBER_INT); 
        $breite = filter_input(INPUT_POST,'breite',FILTER_SANITIZE_NUMBER_INT); 
        $hoehe = filter_input(INPUT_POST,'hoehe',FILTER_SANITIZE_NUMBER_INT); 
        $inhalt = htmlspecialchars($_POST['inhalt']); 
        $freitext = htmlspecialchars($_POST['freitext']); 
        $schriftart = htmlspecialchars($_POST['schriftart']);
        $schriftgroesse = htmlspecialchars($_POST['schriftgroesse']); 
        $schrifttyp = htmlspecialchars($_POST['schrifttyp']);
        $ausrichtung = htmlspecialchars($_POST['ausrichtung']);


        //intinalize Object
        $urkundenelement = ($id === null || $id === false || $id === "") ? new EA_CertificateElement() : $this->EA_CertificateElementRepository->loadById((int)$id);

        //checks for update und create case
        if($x_wert === ""){
            $this->EA_Messages->addMessage("X-Wert nicht ausgefüllt",19123456787,EA_Message::MESSAGE_ERROR);
            return;
        }
        if($y_wert === ""){
            $this->EA_Messages->addMessage("Y-Wert nicht ausgefüllt",194375345444,EA_Message::MESSAGE_ERROR);
            return;
        }

        //set properties
        $urkundenelement->setId($id);
        $urkundenelement->setX_wert($x_wert);
        $urkundenelement->setY_wert($y_wert);
        $urkundenelement->setBreite($breite);
        $urkundenelement->setHoehe($hoehe);
        $urkundenelement->setInhalt($inhalt);
        $urkundenelement->setFreitext($freitext);
        $urkundenelement->setSchriftart($schriftart);
        $urkundenelement->setSchriftgroesse($schriftgroesse);
        $urkundenelement->setSchrifttyp($schrifttyp);
        $urkundenelement->setAusrichtung($ausrichtung);

        
        //create case
        if($urkundenelement->getId() === null){
            $this->EA_CertificateElementRepository->create($urkundenelement);
            $this->EA_Messages->addMessage("Eintrag angelegt",194375345444,EA_Message::MESSAGE_SUCCESS);
        //update case
        }else{
            $this->EA_CertificateElementRepository->update();
            $this->EA_Messages->addMessage("Eintrag geupdated",194375345444,EA_Message::MESSAGE_SUCCESS);

        }
        
    }

    private function editUrkundenelement(): ?EA_CertificateElement
    {
        $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        $urkundenelement = $this->EA_CertificateElementRepository->loadById($id);
        if($urkundenelement === null){
            $this->EA_Messages->addMessage("Keine Urkundenelement gefunden.",195474377777,EA_Message::MESSAGE_WARNINIG);
        }
        return $urkundenelement;
    }

    private function getUrkundenelementList():  string
    {
        $content = "";
        $urkundenelementList = $this->EA_CertificateElementRepository->loadList();
        if ($urkundenelementList !== []) {
            $content = $this->EA_R->renderTabelleUrkundenelemente($urkundenelementList);
        } else {
            $this->EA_Messages->addMessage("Es sind noch keine Urkundenelementen angelegt.",194233454,EA_Message::MESSAGE_WARNINIG);
        } 
        return $content;
    }

    private function deleteUrkundenelement(): void
    {
        $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        $mannschaft = $this->EA_CertificateElementRepository->loadById($id);
        if($mannschaft === null){
            $this->EA_Messages->addMessage("Kein Urkundenelement gefunden.",19237657777,EA_Message::MESSAGE_WARNINIG);
        }else{
            $this->EA_CertificateElementRepository->delete($mannschaft);
            $this->EA_Messages->addMessage("Urkundenelement gelöscht.",1953547777,EA_Message::MESSAGE_SUCCESS);
        }
    }
}
