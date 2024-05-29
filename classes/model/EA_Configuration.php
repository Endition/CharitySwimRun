<?php

namespace CharitySwimRun\classes\model;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
#[ORM\Table(name: 'konfiguration')]
class EA_Configuration
{
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    #[ORM\Id]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING)]
    private string $veranstaltungsname = "";

    #[ORM\Column(type: Types::STRING)]
    private string $veranstaltungslogo = "";

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $transponder = true;

    #[ORM\Column(type: Types::STRING)]
    private string $starttyp = "aba";

    #[ORM\Column(type: Types::STRING)]
    private string $streckenart = "Bahnen";

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $startgruppen = false;

    #[ORM\Column(type: Types::INTEGER)]
    private int $altersklassen = 0;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $start;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $ende;

    #[ORM\Column(type: Types::INTEGER)]
    private int $startgruppenAnzahl = 0;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $mannschaften = false;

    #[ORM\Column(type: Types::INTEGER)]
    private int $mannschaftMitgliedAnzahl = 0;

    #[ORM\Column(type: Types::STRING)]
    private string $mannschaftPunkteBerechnen = "Formel";

    #[ORM\Column(type: Types::STRING)]
    private string $beguenstigter = "";

    #[ORM\Column(type: Types::INTEGER)]
    private int $faktor = 2;

    #[ORM\Column(type: Types::FLOAT)]
    private float $rundenlaenge = 50;

    #[ORM\Column(type: Types::FLOAT)]
    private float $europrometer = 0.05;

    #[ORM\Column(type: Types::FLOAT)]
    private float $geld = 1000;

    #[ORM\Column(type: Types::INTEGER)]
    private int $veranstaltungsrekord = 200000;

    #[ORM\Column(type: Types::INTEGER)]
    private int $teilnehmerrekord = 15000;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $inputEmail = false;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $inputAdresse = false;

    #[ORM\Column(type: Types::STRING)]
    private string $sponsor = "";

    #[ORM\Column(type: Types::INTEGER)]
    private int $lastCalculationResultsNumber = 0;

    private $werte = array(
        "veranstaltungsname" => array(
            "name" => "Veranstaltungsname",
            "type" => "text",
            "pflichtfeld" => true,
            "savedvalue" => "Pro-Cent-Schwimmen",
            "erklaerung" => "Name der Veranstaltung",
            "abhängigkeit" => null
        ),
        "veranstaltungslogo" => array(
            "name" => "Logo",
            "type" => "text",
            "pflichtfeld" => false,
            "savedvalue" => "",
            "erklaerung" => "Hier kann der Dateiname (bild.jpg, logo.gif) des Logos der Veranstaltung angegeben werden. Das Logo muss unter benutzerdateien/ liegen",
            "abhängigkeit" => null
        ),
        "transponder" => array(
            "name" => "Transponder",
            "type" => "select",
            "value" => array(
                1 => "Mit Transponder",
                0 => "Ohne Transponder"
            ),
            "pflichtfeld" => true,
            "savedvalue" => "ja",
            "erklaerung" => "Werden bei der Veranstaltung Transponder benutzt?",
            "abhängigkeit" => null
        ),
        "starttyp" => array(
            "name" => "Starttyp",
            "type" => "select",
            "value" => array(
                "aba" => "Autostart beim Anmelden",
                "ade" => "Autostart durch Einbuchen",
                "ms" => "Massenstart"
            ),
            "pflichtfeld" => true,
            "savedvalue" => "aba",
            "erklaerung" => " Autostart beim Anmelden: Die Startzeit ist gleich die Anmeldezeit, Autostart durch Einbuchen: Die Teilnehmer starten sich selbest durch die erste Buchung;Massenstart: Zentraler Start über die Adminoberfläche.",
            "abhängigkeit" => null
        ),
        "streckenart" => array(
            "name" => "Streckenart",
            "type" => "select",
            "value" => array(
                "Bahnen" => "Bahnen",
                "Runden" => "Runden"
            ),
            "pflichtfeld" => true,
            "savedvalue" => "Runden",
            "erklaerung" => "Die Bezeichnung für die 'Anzahl der Impulse'. Für ein Schwimmbad -> Bahnen, Für eine Laufveranstaltung -> Runden.",
            "abhängigkeit" => null
        ),
        "startgruppen" => array(
            "name" => "Startgruppen",
            "type" => "select",
            "value" => array(
                0 => "Ohne Startgruppen",
                1 => "Mit Startgruppen"
            ),
            "pflichtfeld" => true,
            "savedvalue" => "0",
            "erklaerung" => "Soll es die Möglichkeit geben Startgruppen anzulegen",
            "abhängigkeit" => null
        ),
        "altersklassen" => array(
            "name" => "Altersklassen",
            "type" => "select",
            "value" => array(
                self::AGEGROUPMODUS_BIRTHYEAR => "Jahrgang",
                self::AGEGROUPMODUS_AGE => "genaues Alter"
            ),
            "pflichtfeld" => true,
            "savedvalue" => "0",
            "erklaerung" => "Wie soll die Altersklasse berechnet werden",
            "abhängigkeit" => null
        ),
        "start" => array(
            "name" => "Veranstaltungsbeginn",
            "type" => "datetime-local",
            "pflichtfeld" => true,
            "savedvalue" => "",
            "erklaerung" => "Wann beginnt die Veranstaltung",
            "abhängigkeit" => null
        ),
        "ende" => array(
            "name" => "Veranstaltungsende",
            "type" => "datetime-local",
            "pflichtfeld" => true,
            "savedvalue" => "",
            "erklaerung" => "Wann endet die Veranstaltung",
            "abhängigkeit" => null
        ),
        "mannschaften" => array(
            "name" => "Mannschaften",
            "type" => "select",
            "value" => array(
                0 => "Ohne Mannschaften",
                1 => "Mit Mannschaften"
            ),
            "pflichtfeld" => true,
            "savedvalue" => "0",
            "erklaerung" => "Soll es die Möglichkeit geben Mannschaften anzulegen",
            "abhängigkeit" => null
        ),
        "anzahl_startgruppen" => array(
            "name" => "Anzahl Startgruppen",
            "type" => "number",
            "pflichtfeld" => true,
            "savedvalue" => "0",
            "erklaerung" => " Anzahl der benötigten Startgruppen",
            "abhängigkeit" => array("von" => "startgruppen", "wert" => true)
        ),
        "max_mitglieder" => array(
            "name" => "Mannschaft max. Mitglieder",
            "type" => "number",
            "pflichtfeld" => true,
            "savedvalue" => "0",
            "erklaerung" => "Maximale Anzahl der Mitglieder in einer Mannschaft",
            "abhängigkeit" => array("von" => "mannschaften", "wert" => true)
        ),
        "mannschaft_punkte_berechnung" => array(
            "name" => "Mannschaft Punkteberechnung",
            "type" => "select",
            "value" => array(
                "Gesamtstrecke" => "Gesamtstrecke",
                "Formel" => "Formel"
            ),
            "pflichtfeld" => false,
            "savedvalue" => "Formel",
            "erklaerung" => "Soll bei der Berechnung der Mannschaftspunkte die Gesamtstrecke aller Mitglieder oder eine Berechungsformel angewand werden",
            "abhängigkeit" => array("von" => "mannschaften", "wert" => true)
        ),
        "beguenstigter" => array(
            "name" => "Begünstigter",
            "type" => "text",
            "pflichtfeld" => false,
            "savedvalue" => "",
            "erklaerung" => "Name des beg&uuml;nstigten Vereins f&uuml;r die Urkunden",
            "abhängigkeit" => null

        ),
        "faktor" => array(
            "name" => "Multiplikator",
            "type" => "number",
            "step" => 1,
            "pflichtfeld" => true,
            "savedvalue" => "2",
            "erklaerung" => "Faktor mit dem ein Anschlag am Leser (Impuls) multipliziert wird. Wenn ein Impuls = 2 Bahnen sind: 2 eintragen",
            "abhängigkeit" => null
        ),
        "rundenlaenge" => array(
            "name" => "Rundenlänge",
            "type" => "number",
            "step" => 1,
            "pflichtfeld" => true,
            "savedvalue" => "50",
            "erklaerung" => "L&auml;nge einer Runde (bei zwei Bahnen a 25m = 1 Runde = 50m)",
            "abhängigkeit" => null
        ),
        "europrometer" => array(
            "name" => "Euro pro Meter",
            "type" => "number",
            "step" => 0.001,
            "savedvalue" => "0.005",
            "pflichtfeld" => true,
            "erklaerung" => "Geld, das pro Meter dem Beg&uuml;nstigten gut geschrieben wird",
            "abhängigkeit" => null
        ),
        "geld" => array(
            "name" => "verfügbares Geld",
            "type" => "number",
            "step" => 0.01,
            "savedvalue" => "0",
            "pflichtfeld" => false,
            "erklaerung" => "verf&uuml;gbares Geld (Spendensumme) in Euro",
            "abhängigkeit" => null
        ),
        "veranstaltungsrekord" => array(
            "name" => "Veranstaltungsrekord",
            "type" => "number",
            "savedvalue" => "0",
            "pflichtfeld" => true,
            "erklaerung" => "H&ouml;chste jemals bei einer Veranstaltung zur&uuml;ck gelegte Gesamtmeterzahl",
            "abhängigkeit" => null
        ),
        "teilnehmerrekord" => array(
            "name" => "Teilnehmerrekord",
            "type" => "number",
            "savedvalue" => "0",
            "pflichtfeld" => true,
            "erklaerung" => "H&ouml;chste jemals bei einer Veranstaltung durch einen einzelnen Teilnehmer geschwommene Meterzahl",
            "abhängigkeit" => null
        ),
        "input_adresse" => array(
            "name" => "Anmeldung: Adressfelder",
            "type" => "select",
            "savedvalue" => "0",
            "value" => array(
                1 => "einblenden",
                0 => "ausblenden"
            ),
            "pflichtfeld" => true,
            "erklaerung" => "Sollen PLZ, Wohnort,Straße bei der Anmeldung angezeigt werden oder nicht",
            "abhängigkeit" => null
        ),
        "input_email" => array(
            "name" => "Anmeldung: E-Mail",
            "type" => "select",
            "savedvalue" => 0,
            "value" => array(
                1 => "einblenden",
                0 => "ausblenden"
            ),
            "pflichtfeld" => true,
            "erklaerung" => "Soll E-Mail bei der Anmeldung angezeigt werden oder nicht",
            "abhängigkeit" => null
        ),
        "sponsor" => array(
            "name" => "Sponsor für Log",
            "type" => "text",
            "pflichtfeld" => false,
            "savedvalue" => "",
            "erklaerung" => "Dieser Sponsor erscheint oben in der Log-Anzeige als präsentiert von ",
            "abhängigkeit" => null
        )
    );
    public const AGEGROUPMODUS_BIRTHYEAR = 0;
    public const AGEGROUPMODUS_AGE = 1;

    public function __construct()
    {
        $this->ende = new DateTimeImmutable();
        $this->start = new DateTimeImmutable();
    }

    public function getKonfiguration(): array
    {
        $this->werte['veranstaltungsname']['savedvalue'] = $this->getVeranstaltungsname();
        $this->werte['veranstaltungslogo']['savedvalue'] = $this->getVeranstaltungslogo();
        $this->werte['transponder']['savedvalue'] = (int)$this->getTransponder();
        $this->werte['starttyp']['savedvalue'] = $this->getStarttyp();
        $this->werte['streckenart']['savedvalue'] = $this->getStreckenart();
        $this->werte['startgruppen']['savedvalue'] = (int)$this->getStartgruppen();
        $this->werte['altersklassen']['savedvalue'] = $this->getAltersklassen();
        $this->werte['start']['savedvalue'] = $this->getStart()->format("Y-m-d H:i:s");
        $this->werte['ende']['savedvalue'] = $this->getEnde()->format("Y-m-d H:i:s");
        $this->werte['mannschaften']['savedvalue'] = (int)$this->getMannschaften();
        $this->werte['anzahl_startgruppen']['savedvalue'] = $this->getStartgruppenAnzahl();
        $this->werte['max_mitglieder']['savedvalue'] = $this->getMannschaftMitgliedAnzahl();
        $this->werte['mannschaft_punkte_berechnung']['savedvalue'] = $this->getMannschaftPunkteBerechnen();
        $this->werte['beguenstigter']['savedvalue'] = $this->getBeguenstigter();
        $this->werte['faktor']['savedvalue'] = $this->getFaktor();
        $this->werte['rundenlaenge']['savedvalue'] = $this->getRundenlaenge();
        $this->werte['europrometer']['savedvalue'] = $this->getEuroprometer();
        $this->werte['geld']['savedvalue'] = $this->getGeld();
        $this->werte['veranstaltungsrekord']['savedvalue'] = $this->getVeranstaltungsrekord();
        $this->werte['teilnehmerrekord']['savedvalue'] = $this->getTeilnehmerrekord();
        $this->werte['input_adresse']['savedvalue'] = (int)$this->getInputAdresse();
        $this->werte['input_email']['savedvalue'] = (int)$this->getInputEmail();
        $this->werte['sponsor']['savedvalue'] = $this->getSponsor();
        return $this->werte;
    }

    public function getVeranstaltungsname(): string
    {
        return $this->veranstaltungsname;
    }

    public function setVeranstaltungsname(string $veranstaltungsname): void
    {
        $this->veranstaltungsname = $veranstaltungsname;
    }

    public function getVeranstaltungslogo(): string
    {
        return $this->veranstaltungslogo;
    }

    public function setVeranstaltungslogo(string $veranstaltungslogo): void
    {
        $this->veranstaltungslogo = $veranstaltungslogo;


    }

    public function getTransponder(): bool
    {
        return $this->transponder;
    }


    public function setTransponder(bool $transponder): void
    {
        $this->transponder = $transponder;


    }


    public function getStarttyp(): string
    {
        return $this->starttyp;
    }


    public function setStarttyp(string $starttyp): void
    {
        $this->starttyp = $starttyp;


    }


    public function getStreckenart(): string
    {
        return $this->streckenart;
    }


    public function setStreckenart(string $streckenart): void
    {
        $this->streckenart = $streckenart;
    }

    public function getStartgruppen(): bool
    {
        return $this->startgruppen;
    }

    public function setStartgruppen(bool $startgruppen): void
    {
        $this->startgruppen = $startgruppen;
    }

    public function getStartgruppenAsArray(): ?array
    {
        $startgruppeList = [];
        $anzahl = $this->getStartgruppenAnzahl();
        if ($anzahl == 0) {
            return null;
        } else {
            for ($i = 1; $i <= $anzahl; $i++) {
                $startgruppeList[$i] = $i;
            }
            return $startgruppeList;
        }
    }


    public function getAltersklassen(): int
    {
        return $this->altersklassen;
    }

 
    public function setAltersklassen(int $altersklassen): void
    {
        $this->altersklassen = $altersklassen;


    }


    public function getEnde(): DateTimeImmutable
    {
        return $this->ende;
    }


    public function setEnde(DateTimeImmutable $ende): void
    {
        $this->ende = $ende;
    }

    public function getStart(): DateTimeImmutable
    {
        return $this->start;
    }


    public function setStart(DateTimeImmutable $start): void
    {
        $this->start = $start;
    }


    public function getStartgruppenAnzahl(): int
    {
        return $this->startgruppenAnzahl;
    }

    public function setStartgruppenAnzahl(int $startgruppenAnzahl): void
    {
        $this->startgruppenAnzahl = $startgruppenAnzahl;


    }


    public function getMannschaften(): bool
    {
        return $this->mannschaften;
    }


    public function setMannschaften(bool $mannschaften): void
    {
        $this->mannschaften = $mannschaften;


    }


    public function getMannschaftMitgliedAnzahl(): int
    {
        return $this->mannschaftMitgliedAnzahl;
    }


    public function setMannschaftMitgliedAnzahl(int $mannschaftMitgliedAnzahl): void
    {
        $this->mannschaftMitgliedAnzahl = $mannschaftMitgliedAnzahl;


    }


    public function getMannschaftPunkteBerechnen(): string
    {
        return $this->mannschaftPunkteBerechnen;
    }


    public function setMannschaftPunkteBerechnen(string $mannschaftPunkteBerechnen): void
    {
        $this->mannschaftPunkteBerechnen = $mannschaftPunkteBerechnen;


    }

    public function getBeguenstigter(): string
    {
        return $this->beguenstigter;
    }

    public function setBeguenstigter(string $beguenstigter): void
    {
        $this->beguenstigter = $beguenstigter;


    }


    public function getFaktor(): int 
    {
        return $this->faktor;
    }


    public function setFaktor(int $faktor): void
    {
        $this->faktor = $faktor;


    }


    public function getRundenlaenge(): float
    {
        return $this->rundenlaenge;
    }


    public function setRundenlaenge(float $rundenlaenge): void
    {
        $this->rundenlaenge = $rundenlaenge;


    }

    public function getEuroprometer(): float
    {
        return $this->europrometer;
    }


    public function setEuroprometer(float $europrometer): void
    {
        $this->europrometer = $europrometer;


    }

    public function getGeld(): float
    {
        return $this->geld;
    }

    public function setGeld(float $geld): void
    {
        $this->geld = $geld;


    }

    public function getVeranstaltungsrekord(): int
    {
        return $this->veranstaltungsrekord;
    }

 
    public function setVeranstaltungsrekord($veranstaltungsrekord): void
    {
        $this->veranstaltungsrekord = $veranstaltungsrekord;


    }


    public function getTeilnehmerrekord(): int
    {
        return $this->teilnehmerrekord;
    }


    public function setTeilnehmerrekord($teilnehmerrekord): void
    {
        $this->teilnehmerrekord = $teilnehmerrekord;


    }

    public function getInputEmail(): bool
    {
        return $this->inputEmail;
    }


    public function setInputEmail($inputEmail): void
    {
        $this->inputEmail = $inputEmail;


    }

    public function getInputAdresse(): bool
    {
        return $this->inputAdresse;
    }

  
    public function setInputAdresse($inputAdresse): void
    {
        $this->inputAdresse = $inputAdresse;


    }

    public function getSponsor(): string
    {
        return $this->sponsor;
    }

    public function setSponsor($sponsor): void
    {
        $this->sponsor = $sponsor;


    }

    public function getLastCalculationResultsNumber(): int
    {
        return $this->lastCalculationResultsNumber;
    }

    public function setLastCalculationResultsNumber(int $lastCalculationResultsNumber): void
    {
        $this->lastCalculationResultsNumber = $lastCalculationResultsNumber;
    }

}