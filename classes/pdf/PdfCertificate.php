<?php

namespace CharitySwimRun\classes\pdf;

use Doctrine\ORM\EntityManager;
use CharitySwimRun\classes\core\EA_AbstractPDF;

use CharitySwimRun\classes\model\EA_Starter;
use CharitySwimRun\classes\model\EA_CertificateElementRepository;


class PdfCertificate extends EA_AbstractPDF
{
    private EA_CertificateElementRepository $EA_CertificateElementRepository;

    private $urkundenelemente;




    public function __construct(EntityManager $entiyManager, array $filter)
    {
        parent::__construct("P", "A4", $entiyManager);
        $this->filter = $filter;

        $this->EA_CertificateElementRepository = new EA_CertificateElementRepository($entiyManager);
        $this->urkundenelemente = $this->EA_CertificateElementRepository->loadListQueryBuilder();
        $this->typ = "Urkunde";

        // Setzten der Dokumenteninformationen
        $this->SetTitle($this->typ);
        $this->SetSubject($this->typ);
        $this->SetKeywords('CharitySwimRun,' . $this->typ);
        $this->setPrintFooter(true);
    }

    public function Inhalt()
    { 
        $ergebnisse = $this->InhaltOben();
        foreach ($ergebnisse as $teilnehmer) {
            $this->UrkundeEinzelstarter($teilnehmer);
        }
    }


    // Generiert die Urkunde aus den hinterlegten Daten
    public function UrkundeEinzelstarter(EA_Starter $teilnehmer): void
    {
        // Teilnehmer ggf. überspringen, wenn nur noch bestimmte Statusse ausgegeben werden sollen
        if(!empty($this->filter['status']) && $teilnehmer->getStatus() < $this->filter['status'] ){
            return;
        }

        // Teilnehmer ggf. überspringen, wenn nur TN ausgegeben werden sollen, die einer bestimmten Wertung zugehörigt sind
        if(!empty($this->filter['wertung']) && $teilnehmer->getWertung() != $this->filter['wertung'] ){
            return;
        }
        
        $this->addPage();
        foreach ($this->urkundenelemente as $element) {

            // inhalt berechnene
            switch ($element->getInhalt()) {
                case "Name" :
                    $inhalt = $teilnehmer->getVorname() . " " . $teilnehmer->getName();
                    break;
                case "Meter" :
                    $inhalt = $teilnehmer->getMeter() . "m";
                    break;
                case "Veranstaltungsname" :
                    $inhalt = $this->konfiguration->getVeranstaltungsname();
                    break;
                case "Beguenstigter" :
                    $inhalt = $this->konfiguration->getBeguenstigter();
                    break;
                case "Bahnen/Runden" :
                    $inhalt = $teilnehmer->getStreckenart();
                    break;
                case "Geld" :
                    $inhalt = str_replace(".", ",", $teilnehmer->getGeld()) . " €";
                    break;
                case "Freitext" :
                    $inhalt = $element->getFreitext();
                    break;
                case "Datum" :
                    $inhalt = date("d.m.Y");
                    break;
                case "Zeit" :
                    $inhalt = $teilnehmer->getGesamtzeit() . " Stunden";
                    break;
                case "Strecke" :
                    $inhalt = $teilnehmer->getStrecke()->getBezLang();
                    break;
                case "Altersklasse" :
                    $inhalt = $teilnehmer->getAltersklasse()->getAltersklasse() . " " . $teilnehmer->getGeschlecht();
                    break;
                case "Veranstaltungsname" :
                    $inhalt = $this->konfiguration->getVeranstaltungsname();
                    break;
                case "Gesamtplatz" :
                    $inhalt = $teilnehmer->getStreckenplatz() . ".";
                    break;
                case "AKPlatz" :
                    $inhalt = $teilnehmer->getAkplatz() . ".";
                    break;
            }

            // Font setzen
            $this->SetFont($element->getSchriftart(), $element->getSchrifttyp(), $element->getSchriftgroesse());
            // Text auf die Seite bringen
            $x = $element->getX_wert() / 2;
            $y = $element->getY_wert() / 2;
            $width = $element->getBreite() / 2;
            $height = $element->getHoehe() / 2;
            $this->SetXY($x, $y);
            // Wenn es nicht funzt: Hohe, Breite wieder auf 0,0 setzten und Elemente über die gesamte Seitenbreite verteilen
            $this->Cell($width, $height, $inhalt, 0, 0, '' . $element->getAusrichtung() . '');
            #$this->Cell ( $element->getBreite(), print_r($this->getPageDimensions()), 1, 1, '' . $element->getAusrichtung() . '' );
        }
    }

    public function header()
    {
        //no header needed 
    }

    public function footer()
    {
         //no footer needed 
    }
}

?>