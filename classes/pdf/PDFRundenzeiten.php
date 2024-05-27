<?php

namespace CharitySwimRun\classes\pdf;

use Doctrine\ORM\EntityManager;
use CharitySwimRun\classes\core\EA_AbstractPDF;

use Smarty\Smarty;
use CharitySwimRun\classes\model\EA_StarterRepository;


class PDFRundenzeiten extends EA_AbstractPDF
{

    private array $messages;
    private $id;
    protected string $typ;
    protected Smarty $smarty;
    protected EA_StarterRepository $teilnehmerRepository;

    public function __construct(EntityManager $entityManager, $id)
    {
        parent::__construct("P", "A4", $entityManager);
        $this->id = $id;
        $this->messages = [];
        $this->teilnehmerRepository = new EA_StarterRepository($entityManager);

        $this->typ = "Rundenzeiten";

        // Setzten der Dokumenteninformationen
        $this->SetTitle($this->typ);
        $this->SetSubject($this->typ);
        $this->SetKeywords('CharitySwimRun,' . $this->typ);
        $this->setPrintFooter(true);
    }

    public function Inhalt()
    {
        $this->smarty->assign('impulse', $teilnehmer->getImpulseListGueltige());
        $this->smarty->assign('teilnehmer', $teilnehmer);
        $this->smarty->assign('teilnehmerId', $this->id);
        $this->AddPage();
        $this->setXY(10, 50);
        $this->SetFont('helvetica', '', 11);
        $content = $this->smarty->fetch('DisplayTabelleImpulse.tpl');
        $this->writeHTML($content, true, false, false, false, '');
    }


    public function header()
    {
        $teilnehmer = $this->teilnehmerRepository->loadList(null,null,null,null,null,null,null,"","",null,null,null,null,$this->id);
        // Set font
        $this->SetFont('helvetica', 'B', 12);
        // Title
        $this->setXY(10, 10);
        $this->Cell(0, 15, $this->typ, 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $filterausgabe = "<p>";
        foreach ($teilnehmer as $tn) {
            foreach ($tn->getArrayForString() as $key => $element) {
                $filterausgabe .= "<b>" . $key . ":</b> " . $element . " ### ";
            }
        }
        $filterausgabe .= "</p> ";
        $this->SetFont('helvetica', '', 16);
        $this->writeHTMLCell(0, 0, 10, 20, $filterausgabe, 0);
    }
}

?>