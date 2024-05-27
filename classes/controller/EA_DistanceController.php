<?php
namespace CharitySwimRun\classes\controller;

use Doctrine\ORM\EntityManager;

use CharitySwimRun\classes\model\EA_Distance;
use CharitySwimRun\classes\model\EA_Message;



class EA_DistanceController extends EA_Controller
{

    
    public function __construct( EntityManager $entityManager)
    {
        parent::__construct($entityManager);

    }

    public function getPageStrecken(): string
    {
        $content = "";
        $strecke = new EA_Distance();

        if (isset($_POST['sendStreckeData'])) {
            $this->createAndUpdateStrecke();
        } elseif (isset($_GET['action']) && $_GET['action'] === "edit") {
            $strecke = $this->editStrecke();
        } elseif (isset($_GET['action']) && $_GET['action'] === "delete") {
            $this->deleteStrecke();
        } else {
            $strecke = new EA_Distance();
        }

        $content .= $this->getStreckeList();
        $content .= $this->EA_FR->getFormStrecke($strecke);
        return $content;
    }

    private function createAndUpdateStrecke(): void
    {
        $id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);
        $bezeichnungLang = htmlspecialchars($_POST['bezeichnungLang']);
        $bezeichnungKurz = htmlspecialchars($_POST['bezeichnungKurz']);
        
        //intinalize Object
        $strecke = ($id === null || $id === false || $id === "") ? new EA_Distance() : $this->EA_DistanceRepository->loadById((int)$id);

        //checks for update und create case
        if($bezeichnungLang === ""){
            $this->EA_Messages->addMessage("Bezeichnung (lang) nicht ausgefüllt",1256897777,EA_Message::MESSAGE_ERROR);
            return;
        }
        if($bezeichnungKurz === ""){
            $this->EA_Messages->addMessage("Bezeichnung (kurz) nicht ausgefüllt",12576677777,EA_Message::MESSAGE_ERROR);
            return;
        }

        //checks only for create case
        if($strecke->getId() === null){
            if($this->EA_DistanceRepository->isAvailable("bezLang", $bezeichnungLang) === false){
                $this->EA_Messages->addMessage("Die Bezeichnung {$bezeichnungLang} für die Strecke ist schon vergeben",1459789787,EA_Message::MESSAGE_ERROR);
                return;
            }
            if($this->EA_DistanceRepository->isAvailable("bezKurz", $bezeichnungKurz) === false){
                $this->EA_Messages->addMessage("Die Bezeichnung {$bezeichnungKurz} für die Strecke ist schon vergeben",1456787777,EA_Message::MESSAGE_ERROR);
                return;
            }
        }
        
        //set properties
        $strecke->setBezKurz($bezeichnungKurz);
        $strecke->setBezLang($bezeichnungLang);
        
        //create case
        if($strecke->getId() === null){
            $this->EA_DistanceRepository->create($strecke);
            $this->EA_Messages->addMessage("Eintrag angelegt",193478775454,EA_Message::MESSAGE_SUCCESS);
        //update case
        }else{
            $this->EA_DistanceRepository->update();
            $this->EA_Messages->addMessage("Eintrag geändert",19233777772,EA_Message::MESSAGE_SUCCESS);
        }
        
    }

    private function editStrecke(): ?EA_Distance
    {
        $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        $strecke = $this->EA_DistanceRepository->loadById($id);
        if($strecke === null){
            $this->EA_Messages->addMessage("Keine Strecke gefunden.",156567875,EA_Message::MESSAGE_WARNINIG);
        }
        return $strecke;
    }

    private function getStreckeList():  string
    {
        $content = "";
        $streckeList = $this->EA_DistanceRepository->loadList();
        if ($streckeList !== []) {
            $content = $this->EA_R->renderTabelleStrecken($streckeList);
        } else {
            $this->EA_Messages->addMessage("Es sind noch keine Streckeen angelegt.",155677878,EA_Message::MESSAGE_WARNINIG);
        } 
        return $content;
    }

    private function deleteStrecke(): void
    {
        $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        $strecke = $this->EA_DistanceRepository->loadById($id);

        if($strecke === null){
            $this->EA_Messages->addMessage("Keine Strecke gefunden.",1956354634562,EA_Message::MESSAGE_ERROR);
            return;
        }
        if($this->EA_DistanceRepository->isInUse($strecke) === true){
            $this->EA_Messages->addMessage("Strecke ist noch in Benutzung.",18345345322,EA_Message::MESSAGE_ERROR);
            return;
        }

        $this->EA_DistanceRepository->delete($strecke);
        $this->EA_Messages->addMessage("Strecke gelöscht.",1236234235,EA_Message::MESSAGE_SUCCESS);
    }
}