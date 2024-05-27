<?php

namespace EndeAuswertung\classes\controller;


use EndeAuswertung\classes\model\EA_Repository;
use EndeAuswertung\classes\pdf\PDFMeldeliste;
use EndeAuswertung\classes\pdf\PDFErgebnisliste;
use EndeAuswertung\classes\pdf\PDFUrkunde;
use EndeAuswertung\classes\pdf\PDFRundenzeiten;

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
            $pdf = $this->getPdfMeldeliste();
        } elseif (isset($_GET['doc']) && $_GET['doc'] === "ergebnisse") {
            $pdf = $this->getPdfErgebnisliste();
        } elseif (isset($_GET['doc']) && $_GET['doc'] === "urkunden") {
            $pdf = $this->getPdfUrkunde();
        } elseif (isset($_GET['doc']) && $_GET['doc'] === "rundenzeiten") {
            $pdf = $this->getPdfRundenzeiten();
        }

        $pdf->Inhalt();
        //OB_Clean entfernt viele Ausgaben, verhinert aber die Ausgabe von xprint();
        ob_clean();
        $dateiname = $_GET['doc'] . "_" . $filter['typ'] . "_" . $filter['strecke'] . "_" . $filter['altersklasse'] . "_" . $filter['geschlecht'];
        $pdfdestination = "D";
        return $pdf->Output($dateiname . '.pdf', $pdfdestination);
    }

    private function getPdfMeldeliste(): PDFMeldeliste
    {
        return new PDFMeldeliste($this->entityManager, $this->getFilter());
    }

    private function getPdfErgebnisliste(): PDFErgebnisliste
    {
        return new PDFErgebnisliste($this->entityManager, $this->getFilter());
    }

    private function getPdfRundenzeiten(): PDFRundenzeiten
    {
        return new PDFRundenzeiten($this->entityManager, $_GET['id']);
    }

    private function getPdfUrkunde(): PDFUrkunde
    {
        $filter = $this->getFilter();
        //Hier den Sonderfall Einzelurkunde abfangen
        if ((isset($_POST['id']) && isset($_POST['sendTeilnehmerDruckenData']))) {
          $filter['typ'] = "Einzelstarter";
          $filter['id'] = $_POST['id'];
        } elseif ((isset($_GET['id']) && ctype_digit($_GET['id']))) {
          $filter['typ'] = "Einzelstarter";
                        $filter['id'] = $_GET['id'];
                    }
        return new PDFUrkunde($this->entityManager, $filter);
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
            $this->EA_MannschaftRepository->MannschaftPunkteBerechnen(null,$this->konfiguration);
        } elseif (isset($_GET['typ']) && $_GET['typ'] === "Vereine" && $_GET['Strecke']) {
            $filter['typ'] = "Vereine";
        }
        return $filter;
    }
}