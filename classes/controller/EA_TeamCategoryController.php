<?php
namespace CharitySwimRun\classes\controller;

use Doctrine\ORM\EntityManager;


use CharitySwimRun\classes\model\EA_TeamCategory;
use CharitySwimRun\classes\model\EA_Message;

class EA_TeamCategoryController extends EA_Controller
{
    public function __construct( EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function getPageMannschaftskategorie(): string
    {
        $content = "";
        $mannschaftskategorieBez = new EA_TeamCategory();

        if (isset($_POST['sendMannschaftskategorieData'])) {
            $this->createAndUpdateMannschaftskategorie();
        } elseif (isset($_GET['action']) && $_GET['action'] === "edit") {
            $mannschaftskategorieBez = $this->editMannschaftskategorie();
        } elseif (isset($_GET['action']) && $_GET['action'] === "delete") {
            $this->deleteMannschaftskategorie();
        } else {
            $mannschaftskategorieBez = new EA_TeamCategory();
        }

        $content .= $this->getMannschaftskategorieList();

        $content .= $this->EA_FR->getFormMannschaftskategorie($mannschaftskategorieBez);
        return $content;
    }

    private function createAndUpdateMannschaftskategorie(): void
    {
        $id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);
        $mannschaftskategorieBez = htmlspecialchars($_POST['mannschaftskategorie']);


        if(strlen($mannschaftskategorieBez) < 8){
            $this->EA_Messages->addMessage("Kategorie muss min. 8 Zeichen lang sein",199834325234,EA_Message::MESSAGE_ERROR);
            return;
        }

        if($id === null || $id === false || $id === ""){
            if($this->EA_TeamCategoryRepository->loadByBezeichnung($mannschaftskategorieBez) !== null){
                $this->EA_Messages->addMessage("Diese Bezeichnung für die Kategorie ist schon vergeben",17893452345,EA_Message::MESSAGE_ERROR);
                return;
            }else{
                $mannschaftskategorie = new EA_TeamCategory();
                $mannschaftskategorie->setMannschaftskategorie($mannschaftskategorieBez);
                $this->EA_TeamCategoryRepository->create($mannschaftskategorie);
                $this->EA_Messages->addMessage("Eintrag angelegt",1235437737,EA_Message::MESSAGE_SUCCESS);

            }
        }else{
            $mannschaftskategorie = $this->EA_TeamCategoryRepository->loadById((int)$id);
            $mannschaftskategorie->setMannschaftskategorie($mannschaftskategorieBez);
            $this->EA_TeamCategoryRepository->update();
            $this->EA_Messages->addMessage("Eintrag geändert",1257677777,EA_Message::MESSAGE_SUCCESS);

        }
    }

    private function editMannschaftskategorie(): ?EA_TeamCategory
    {
        $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        $mannschaftskategorieBez = $this->EA_TeamCategoryRepository->loadById($id);
        if($mannschaftskategorieBez === null){
            $this->EA_Messages->addMessage("Keine Kategorie gefunden.",12434523452,EA_Message::MESSAGE_WARNING);
        }
        return $mannschaftskategorieBez;
    }

    private function getMannschaftskategorieList():  string
    {
        $content = "";
        $mannschaftskategorieList = $this->EA_TeamCategoryRepository->loadList();
        if ($mannschaftskategorieList !== []) {
            $content = $this->EA_R->renderTabelleMannschaftskategorien($mannschaftskategorieList);
        } else {
            $this->EA_Messages->addMessage("Es sind noch keine Mannschaftskategorien angelegt.",535756423234,EA_Message::MESSAGE_WARNING);
        } 
        return $content;
    }

    private function deleteMannschaftskategorie(): void
    {
        $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        $mannschaftskategorieBez = $this->EA_TeamCategoryRepository->loadById($id);
        if($mannschaftskategorieBez === null){
            $this->EA_Messages->addMessage("Kein Mannschaftskategorie gefunden.",1624435643,EA_Message::MESSAGE_WARNING);
        }else{
            $this->EA_TeamCategoryRepository->delete($mannschaftskategorieBez);
            $this->EA_Messages->addMessage("Mannschaftskategorie gelöscht.",123456,EA_Message::MESSAGE_SUCCESS);
        }
    }

}