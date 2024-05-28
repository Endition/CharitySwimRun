<?php
namespace CharitySwimRun\classes\controller;

use Doctrine\ORM\EntityManager;

use CharitySwimRun\classes\model\EA_Team;
use CharitySwimRun\classes\model\EA_Message;


class EA_TeamController extends EA_Controller
{
   
    public function __construct( EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function getPageMannschaften(): string
    {
        $content = "";
        $mannschaft = new EA_Team();

        if (isset($_POST['sendMannschaftData'])) {
            $this->createAndUpdateMannschaft();
        } elseif (isset($_GET['action']) && $_GET['action'] === "edit") {
            $mannschaft = $this->editMannschaft();
        } elseif (isset($_GET['action']) && $_GET['action'] === "delete") {
            $this->deleteMannschaft();
        } else {
            $mannschaft = new EA_Team();
        }

        $content .= $this->getMannschaftList();

        $content .= $this->EA_FR->getFormMannschaft($mannschaft,$this->EA_TeamCategoryRepository->loadListForSelect());
        return $content;
    }

    private function createAndUpdateMannschaft(): void
    {
        $id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);
        $bezeichnung = htmlspecialchars($_POST['bezeichnung']);
        $startnummer = filter_input(INPUT_POST, "startnummer", FILTER_SANITIZE_NUMBER_INT);
        $verantwortlicherName = htmlspecialchars($_POST['ver_name']);
        $verantwortlicherVorname =  htmlspecialchars($_POST['ver_vorname']);
        $verantwortlicherEmail =    htmlspecialchars($_POST['ver_mail']);
        $mannschaftskategorieId = filter_input(INPUT_POST, "mannschaftskategorieId", FILTER_SANITIZE_NUMBER_INT);
        $mannschaftskategorie = $this->EA_TeamCategoryRepository->loadById($mannschaftskategorieId);
        
        //intinalize Object
        $mannschaft = ($id === null || $id === false || $id === "") ? new EA_Team() : $this->EA_TeamRepository->loadById((int)$id);

        //checks for update und create case
        if(strlen($bezeichnung) < 8){
            $this->EA_Messages->addMessage("Mannschaftsbezeichnung muss min. 8 Zeichen lang sein",14646546556,EA_Message::MESSAGE_ERROR);
            return;
        }
        if($mannschaftskategorie === null){
            $this->EA_Messages->addMessage("Mannschaftskategorie nicht gesetzt",4454567877,EA_Message::MESSAGE_ERROR);
            return;
        }

        //checks only for create case
        if($mannschaft->getId() === null){
            if($this->EA_TeamRepository->isAvailable("mannschaft", $bezeichnung) === false){
                $this->EA_Messages->addMessage("Die Bezeichnung {$bezeichnung} für die Mannschaft ist schon vergeben",1834344545,EA_Message::MESSAGE_ERROR);
                return;
            }
            if($this->EA_TeamRepository->isAvailable("startnummer", $startnummer) === false){
                $this->EA_Messages->addMessage("Die Startnummer {$startnummer} für die Mannschaft ist schon vergeben",17534546457,EA_Message::MESSAGE_ERROR);
                return;
            }
        }
        
        //set properties
        $mannschaft->setMannschaft($bezeichnung);
        $mannschaft->setStartnummer($startnummer);
        $mannschaft->setVer_name($verantwortlicherName);
        $mannschaft->setVer_vorname($verantwortlicherVorname);
        $mannschaft->setVer_mail($verantwortlicherEmail);
        $mannschaft->setMannschaftskategorie($mannschaftskategorie);
        
        //create case
        if($mannschaft->getId() === null){
            $this->EA_TeamRepository->create($mannschaft);
            $this->EA_Messages->addMessage("Eintrag angelegt",14357555467,EA_Message::MESSAGE_SUCCESS);
        //update case
        }else{
            $this->EA_TeamRepository->update();
            $this->EA_Messages->addMessage("Eintrag geändert",1235777317,EA_Message::MESSAGE_SUCCESS);
        }
        
    }

    private function editMannschaft(): ?EA_Team
    {
        $id = filter_input(INPUT_POST,'mannschaftsid',FILTER_SANITIZE_NUMBER_INT);
        $mannschaft = $this->EA_TeamRepository->loadById($id);
        if($mannschaft === null){
            $this->EA_Messages->addMessage("Keine Mannschaft gefunden.",18323134,EA_Message::MESSAGE_WARNING);
        }
        return $mannschaft;
    }

    private function getMannschaftList():  string
    {
        $content = "";
        $mannschaftList = $this->EA_TeamRepository->loadList();
        if ($mannschaftList !== []) {
            $content = $this->EA_FR->getFormSelectMannschaften($mannschaftList);
        } else {
            $this->EA_Messages->addMessage("Es sind noch keine Mannschaften angelegt.",184135773,EA_Message::MESSAGE_WARNING);
        } 
        return $content;
    }

    private function deleteMannschaft(): void
    {
        $id = filter_input(INPUT_POST,'mannschaftsid',FILTER_SANITIZE_NUMBER_INT);
        $mannschaft = $this->EA_TeamRepository->loadById($id);
        if($mannschaft->getMitgliederList()->count() > 0){
            $this->EA_Messages->addMessage("Mannschaft hat noch Mitglieder",1864322742,EA_Message::MESSAGE_ERROR);
            return;
        }
        if($mannschaft === null){
            $this->EA_Messages->addMessage("Kein Mannschaft gefunden.",1864322742,EA_Message::MESSAGE_WARNING);
        }else{
            $this->EA_TeamRepository->delete($mannschaft);
            $this->EA_Messages->addMessage("Mannschaft gelöscht.",1833747377,EA_Message::MESSAGE_SUCCESS);
        }
    }
}