<?php

namespace EndeAuswertung\classes\model;
use EndeAuswertung\classes\model\EA_Urkunde;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
#[ORM\Table(name: 'urkunden')]
class EA_Urkundenelement extends EA_Urkunde
{
    #[ORM\Column(type: Types::INTEGER, name:"ID")]
    #[ORM\GeneratedValue]
    #[ORM\Id]
    private ?int $id = null;

    #[ORM\Column(type: Types::INTEGER, name:"xWert")]
    private int $x_wert = 0;

    #[ORM\Column(type: Types::INTEGER, name:"yWert")]
    private int $y_wert = 0;

    #[ORM\Column(type: Types::INTEGER, name:"Breite")]
    private int $breite = 0;

    #[ORM\Column(type: Types::INTEGER, name:"Hoehe")]
    private int $hoehe = 0;

    #[ORM\Column(type: Types::STRING, name:"Inhalt")]
    private string $inhalt = "";

    #[ORM\Column(type: Types::STRING, name:"Freitext")]
    private string $freitext = "";

    #[ORM\Column(type: Types::STRING, name:"Schriftart")]
    private string $schriftart = "";

    #[ORM\Column(type: Types::FLOAT, name:"Schriftgroesse")]
    private float $schriftgroesse = 11.0;

    #[ORM\Column(type: Types::STRING, name:"Schrifttyp")]
    private string $schrifttyp = "";

    #[ORM\Column(type: Types::STRING, name:"Ausrichtung")]
    private string $ausrichtung = "";

    public function __construct()
    {

    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getX_wert(): int
    {
        return $this->x_wert;
    }

    public function setX_wert(int $x_wert): void
    {
        $this->x_wert = $x_wert;
    }

    public function getY_wert(): int
    {
        return $this->y_wert;
    }

    public function setY_wert(int $y_wert): void
    {
        $this->y_wert = $y_wert;
    }

    public function getBreite(): int
    {
        return $this->breite;
    }

    public function setBreite(int $breite): void
    {
        $this->breite = $breite;
    }

    public function getHoehe(): int
    {
        return $this->hoehe;
    }

    public function setHoehe(int $hoehe): void
    {
        $this->hoehe = $hoehe;
    }

    public function getInhalt(): string
    {
        return $this->inhalt;
    }

    public function setInhalt(string $inhalt): void
    {
        $this->inhalt = $inhalt;
    }

    public function getFreitext(): string
    {
        return $this->freitext;
    }

    public function setFreitext(string $freitext): void
    {
        $this->freitext = $freitext;
    }

    public function getSchriftart(): string
    {
        return $this->schriftart;
    }

    public function getInhaltFreitext(): string
    {
        if ($this->inhalt === "Freitext") {
            return $this->freitext;
        } else {
            return $this->inhalt;
        }
    }

    public function setSchriftart(string $schriftart): void
    {
        $this->schriftart = $schriftart;
    }

 
    public function getSchriftgroesse(): float
    {
        return $this->schriftgroesse;
    }

    public function setSchriftgroesse(float $schriftgroesse): void
    {
        $this->schriftgroesse = $schriftgroesse;
    }

    public function getSchrifttyp(): string
    {
        return $this->schrifttyp;
    }

    public function setSchrifttyp(string $schrifttyp): void
    {
        $this->schrifttyp = $schrifttyp;
    }

    public function getAusrichtung(): string
    {
        return $this->ausrichtung;
    }

    public function setAusrichtung(string $ausrichtung): void
    {
        $this->ausrichtung = $ausrichtung;
    }

    public function setStandardValues(int $x, int $y, int $breite, int $hoehe, string $inhalt, string $freitext, int $schriftgroesse, string $schriftart, string $schrifttyp, string $ausrichtung): void
    {
        $this->setX_wert($x);
        $this->setY_wert($y);
        $this->setBreite($breite);
        $this->setHoehe($hoehe);
        $this->setInhalt($inhalt);
        $this->setFreitext($freitext);
        $this->setSchriftgroesse($schriftgroesse);
        $this->setSchriftart($schriftart);
        $this->setSchrifttyp($schrifttyp);
        $this->setAusrichtung($ausrichtung);
    }

}