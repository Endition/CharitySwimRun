<?php
namespace CharitySwimRun\classes\model;

class EA_Certificate
{
    public const INHALT_SELECTVALUES = array("Gesamtname" => "Gesamtname",
            "Meter" => "Meter",
            "Freitext" => "Freitext",
            "Bahnen/Runden" => "Bahnen/Runden",
            "Beguenstigter" => "Begünstigter",
            "Geld" => "Geld",
            "Zeit" => "Zeit",
            "Datum" => "Datum",
            "Strecke" => "Strecke",
            "Altersklasse" => "Altersklasse",
            "Veranstaltungsname" => "Veranstaltungsname",
            "Gesamtplatz" => "Gesamtplatz",
            "AKPlatz" => "Platz in der AK");
    
    public const SCHRIFTART_SELECTVALUES = array("Helvetica" => "Helvetica", "Times" => "Times", "Courier" => "Courier");
    
    public const SCHRIFTTYP_SELECTVALUES =  array(" " => "normal", "B" => "Fett", "U" => "Unterstrichen", "I" => "Kursiv");
     
    public const AUSRICHTUNG_SELECTVALUES= array("C" => "zentriert", "L" => "links", "R" => "rechts");

    public static function getStandardElemente(): array
    {
        $list[0] = new EA_CertificateElement();
        $list[0]->setStandardValues(126, 158, 94, 30, 'Name', '', 35, 'Helvetica', 'B', 'C');
        $list[1] = new EA_CertificateElement();
        $list[1]->setStandardValues(113, 211, 122, 18, 'Freitext', 'hat beim', 24, 'Helvetica', ' ', 'C');
        $list[2] = new EA_CertificateElement();
        $list[2]->setStandardValues(30, 309, 94, 26, 'Meter', '', 28, 'Helvetica', 'B', 'R');
        $list[3] = new EA_CertificateElement();
        $list[3]->setStandardValues(189, 310, 94, 22, 'Geld', '', 28, 'Helvetica', 'B', 'L');
        $list[4] = new EA_CertificateElement();
        $list[4]->setStandardValues(65, 353, 202, 22, 'Freitext', 'zu Gunsten der', 24, 'Helvetica', ' ', 'C');
        $list[5] = new EA_CertificateElement();
        $list[5]->setStandardValues( 136, 256, 54, 30, 'Freitext', ' VERANSTALTUNG', 28, 'Helvetica', 'B', 'C');
        $list[6] = new EA_CertificateElement();
        $list[6]->setStandardValues(64, 385, 209, 19, 'Freitext', 'VEREIN', 25, 'Helvetica', ' ', 'C');
        $list[7] = new EA_CertificateElement();
        $list[7]->setStandardValues(69, 418, 194, 30, 'Freitext', 'erschwommen.', 25, 'Helvetica', ' ', 'C');
        $list[8] = new EA_CertificateElement();
        $list[8]->setStandardValues(144, 311, 17, 21, 'Freitext', '=', 28, 'Helvetica', ' ', 'C');
        return $list;
    }
}
