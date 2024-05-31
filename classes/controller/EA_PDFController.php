<?php

namespace CharitySwimRun\classes\controller;


use CharitySwimRun\classes\model\EA_Repository;
use CharitySwimRun\classes\pdf\PdfReportList;
use CharitySwimRun\classes\pdf\PdfResultlist;
use CharitySwimRun\classes\pdf\PdfCertificate;

class EA_PDFController extends EA_Controller
{
    public function __construct(EA_Repository $EA_Repository)
    {
        parent::__construct($EA_Repository->getEntityManager());
    }

    public function getPDFAdmin(): string
    {
        $filter = $this->getFilter();

        //generieren des PDFs
        if (isset($_GET['doc']) && $_GET['doc'] === "meldelisten") {
            $pdf = $this->getPdfReportList();
        } elseif (isset($_GET['doc']) && $_GET['doc'] === "ergebnisse") {
            $pdf = $this->getPdfResultlist();
        } elseif (isset($_GET['doc']) && $_GET['doc'] === "urkunden") {
            $pdf = $this->getPdfCertificate();
        } 

        $pdf->Inhalt();
        //OB_Clean entfernt viele Ausgaben, verhinert aber die Ausgabe von xprint();
        ob_clean();
        $dateiname = htmlspecialchars($_GET['doc']) . "_" . $filter['typ'] . "_" . $filter['strecke'] . "_" . $filter['altersklasse'] . "_" . $filter['geschlecht'];
        $pdfdestination = "D";
        return $pdf->Output($dateiname . '.pdf', $pdfdestination);
    }

    private function getPdfReportList(): PdfReportList
    {
        return new PdfReportList($this->entityManager, $this->getFilter());
    }

    private function getPdfResultlist(): PdfResultlist
    {
        return new PdfResultlist($this->entityManager, $this->getFilter());
    }

    private function getPdfCertificate(): PdfCertificate
    {
        $filter = $this->getFilter();
        //Hier den Sonderfall Einzelurkunde abfangen
        if ((isset($_POST['id']) && isset($_POST['sendTeilnehmerDruckenData']))) {
          $filter['typ'] = "Einzelstarter";
          $filter['id'] = filter_input(INPUT_POST,"id",FILTER_SANITIZE_NUMBER_INT);
        } elseif ((isset($_GET['id']) && ctype_digit($_GET['id']))) {
          $filter['typ'] = "Einzelstarter";
                 $filter['id'] = filter_input(INPUT_GET,"id",FILTER_SANITIZE_NUMBER_INT);
        }
        return new PdfCertificate($this->entityManager, $filter);
    }

    private function getFilter(): array
    {
        $filter = [];
        $filter['typ'] = "Einzelstarter";
        $filter['strecke'] =  filter_input(INPUT_GET,"Strecke",FILTER_SANITIZE_NUMBER_INT);
        $filter['altersklasse'] = filter_input(INPUT_GET,"Altersklasse",FILTER_SANITIZE_NUMBER_INT);
        $filter['geschlecht'] = isset($_GET['Geschlecht']) ? htmlspecialchars($_GET['Geschlecht']) : null;
        $filter['startgruppe'] = filter_input(INPUT_GET,"Startgruppe",FILTER_SANITIZE_NUMBER_INT);
        $filter['alle'] = null;
        $filter["Startzeit"] = true;
        $filter["hatBuchung"] = true;
        $filter["order"] = ($_GET['doc'] === "meldelisten") ? "name" : "gesamtplatz";
        $filter["orderRichtung"] = "ASC";
        $filter['status'] = filter_input(INPUT_GET,"status",FILTER_SANITIZE_NUMBER_INT);
        $filter['id'] = null;
        if (isset($_GET['typ']) && $_GET['typ'] === "Einzelstarter") {
            $filter['typ'] = "Einzelstarter";
            $filter['alle'] = isset($_GET['alle']) ? htmlspecialchars($_GET['alle']) : null;
        } elseif (isset($_GET['typ']) && $_GET['typ'] === "Mannschaften" && $_GET['Strecke']) {
            $filter['typ'] = "Mannschaften";
            $this->EA_TeamRepository->MannschaftPunkteBerechnen(null,$this->konfiguration);
        } elseif (isset($_GET['typ']) && $_GET['typ'] === "Vereine" && $_GET['Strecke']) {
            $filter['typ'] = "Vereine";
        }
        return $filter;
    }
}