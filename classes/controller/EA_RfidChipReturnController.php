<?php
namespace CharitySwimRun\classes\controller;

use Doctrine\ORM\EntityManager;

use CharitySwimRun\classes\model\EA_Starter;
use CharitySwimRun\classes\model\EA_Message;

class EA_RfidChipReturnController extends EA_Controller
{

    public function __construct( EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function getPageTransponderrueckgabe(): string
    {
        $content = "";
        if (isset($_POST['sendTransponderRueckgabeData'])) {
            if (isset($_POST['sendTransponderRueckgabeData']) && ctype_digit($_POST['sendTransponderRueckgabeData'])) {
                $id = $_POST['sendTransponderRueckgabeData'];
                $EA_T = $this->EA_StarterRepository->loadById($id);
            }
            $transponder = $EA_T->getTransponder();
            $EA_T->setTransponder(null);
            $EA_T->setStatus(EA_Starter::STATUS_TRANSPONDER_ZURUECKGEGEBEN);
            $this->EA_StarterRepository->update();
            $this->EA_Messages->addMessage("Transponder " . $transponder . " ausgebucht",13235474663,EA_Message::MESSAGE_SUCCESS);
        }
        $content .= $this->EA_FR->getFormTransponderrueckgabe($this->EA_StarterRepository->loadList(null,null,null,null,null,null,false,"transponder"));
        return $content;
    }

}