<?php

namespace CharitySwimRun\classes\pdf;

use Doctrine\ORM\EntityManager;
use CharitySwimRun\classes\core\EA_AbstractPDF;

class PdfResultlist extends EA_AbstractPDF
{
  

    public function __construct(EntityManager $entityManager, array $filter)
    {
        parent::__construct("L", "A4", $entityManager);
        
        $this->filter = $filter;
        $this->smarty->assign('filter', $filter);

        $this->typ = "Meldeliste " . $this->konfiguration->getVeranstaltungsname();

        // Setzten der Dokumenteninformationen
        $this->SetTitle($this->typ);
        $this->SetSubject($this->typ);
        $this->SetKeywords('CharitySwimRun,' . $this->typ);
        $this->setPrintFooter(true);
    }

    public function Inhalt()
    { 
       $this->InhaltOben();
    }


    protected function getContent(?string $ueberschrift = null): void
    {
        $this->AddPage();
        $this->SetXY(10, 17);
        $this->SetFont('helvetica', 'B', 14);
        $this->Cell(0, 15, $ueberschrift, 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->SetXY(10, 20);
        if ($this->filter['typ'] === "Vereine") {
            $content = $this->smarty->fetch('PdfResultClubTable.tpl');
        } elseif ($this->filter['typ'] === "Mannschaften") {
            $content = $this->smarty->fetch('PdfResultTeamTable.tpl');
        } else {
            $content = $this->smarty->fetch('PdfResultStarterTable.tpl');
        }

        $this->SetXY(10, 30);
        $this->SetFont('helvetica', '', 11);
        $this->writeHTML($content, true, false, false, false, '');
    }

    public function header()
    {
        $this->SetFont('helvetica', 'B', 16);
        $this->setXY(10, 10);
        $this->Cell(0, 15, $this->typ, 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $filterausgabe = "<p>Gefiltert: ";
        if ($this->filter['typ'] !== null) {
            $filterausgabe .= " <b>Typ:</b> " . $this->filter['typ'];
        }
        if ($this->filter['strecke'] !== null) {
            $filterausgabe .= " <b>Strecke:</b> " . $this->streckeList[$this->filter['strecke']]->getBezLang();
        }
        if ($this->filter['altersklasse'] !== null) {
            $filterausgabe .= " <b>Altersklasse:</b> " . $this->altersklasseList[$this->filter['altersklasse']]->getAltersklasse();
        }
        if ($this->filter['geschlecht'] !== null && $this->filter['geschlecht'] != "MW") {
            $filterausgabe .= " <b>Geschlecht:</b> " . $this->geschlechter[$this->filter['geschlecht']];
        }
        if ($this->filter['startgruppe'] !== null) {
            $filterausgabe .= " <b>Startgruppe:</b> " . $this->filter['startgruppe'];
        }
        $filterausgabe .= "</p> ";
        $this->SetFont('helvetica', '', 16);
        $this->writeHTMLCell(0, 0, 10, 20, $filterausgabe, 0, 1);
        $this->SetTopMargin($this->GetY());
    }
}

?>