<?php

namespace CharitySwimRun\classes\model;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'aks')]
class EA_AgeGroup
{
    #[ORM\Column(type: Types::INTEGER,name:"Id")]
    #[ORM\GeneratedValue]
    #[ORM\Id]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING,name:"AK",length:50)]
    private string $altersklasse = "";

    #[ORM\Column(type: Types::STRING,name:"AK_kurz",length:50)]
    private string $altersklasseKurz = "";

    #[ORM\Column(type: Types::DATE_IMMUTABLE,name:"u_Datum",nullable:true)]
    private ?DateTimeImmutable $uDatum = null;
    
    #[ORM\Column(type: Types::DATE_IMMUTABLE,name:"o_Datum",nullable:true)]
    private ?DateTimeImmutable $oDatum = null;

    #[ORM\Column(type: Types::INTEGER,name:"StartAlter",options:["default"=>0])]
    private int $startAlter = 0;

    #[ORM\Column(type: Types::INTEGER,name:"EndeAlter",options:["default"=>0])]
    private int $endeAlter = 0;

    #[ORM\Column(type: Types::FLOAT,name:"Startgeld")]
    private float $startgeld = 0.0;

    #[ORM\Column(type: Types::FLOAT,name:"TPGeld")]
    private float $tpgeld = 0.0;

    #[ORM\Column(type: Types::FLOAT,name:"Wertungsschluessel",nullable:true,options:["default"=>null])]
    private ?float $wertungsschluessel = null;

    #[ORM\Column(type: Types::INTEGER,name:"Rekord",nullable:true,options:["default"=>null])]
    private ?int $rekord = null;

    #[ORM\Column(type: Types::INTEGER,name:"Urkunde",nullable:true,options:["default"=>null])]
    private ?int $urkunde = null;

    #[ORM\Column(type: Types::INTEGER,name:"Bronze",nullable:true,options:["default"=>null])]
    private ?int $bronze = null;

    #[ORM\Column(type: Types::INTEGER,name:"Silber",nullable:true,options:["default"=>null])]
    private ?int $silber = null;

    #[ORM\Column(type: Types::INTEGER,name:"Gold",nullable:true,options:["default"=>null])]
    private ?int $gold = null;

    #[ORM\OneToMany(targetEntity: EA_Starter::class, mappedBy: 'altersklasse')]
    private Collection $mitgliederList;

    private $datenarray = [];

    /* http://www.lvrheinland.de/service/wissenswertes/altersklassenbezeichnungen-gemaess-dlo-fuer-2017*/
    private array $akBezGemDLO2017 = array(
        0 => array("Name" => "Kinder U8", "Kurz" => "K_U8", "uDatum-minus" => 7, "oDatum-minus" => 0, "Urkunde" => 0, "Bronze" => 0, "Silber" => 0, "Gold" => 0),
        1 => array("Name" => "Kinder U10", "Kurz" => "K_U10", "uDatum-minus" => 9, "oDatum-minus" => 8, "Urkunde" => 0, "Bronze" => 0, "Silber" => 0, "Gold" => 0),
        2 => array("Name" => "Kinder U12", "Kurz" => "K_U12", "uDatum-minus" => 11, "oDatum-minus" => 10, "Urkunde" => 0, "Bronze" => 0, "Silber" => 0, "Gold" => 0),
        3 => array("Name" => "Jugend U14", "Kurz" => "J_U14", "uDatum-minus" => 13, "oDatum-minus" => 12, "Urkunde" => 0, "Bronze" => 0, "Silber" => 0, "Gold" => 0),
        4 => array("Name" => "Jugend U16", "Kurz" => "J_U16", "uDatum-minus" => 15, "oDatum-minus" => 14, "Urkunde" => 0, "Bronze" => 0, "Silber" => 0, "Gold" => 0),
        5 => array("Name" => "Jugend U18", "Kurz" => "J_U18", "uDatum-minus" => 17, "oDatum-minus" => 16, "Urkunde" => 0, "Bronze" => 0, "Silber" => 0, "Gold" => 0),
        6 => array("Name" => "Jugend U20", "Kurz" => "J_U20", "uDatum-minus" => 19, "oDatum-minus" => 18, "Urkunde" => 0, "Bronze" => 0, "Silber" => 0, "Gold" => 0),
        7 => array("Name" => "Junioren U23", "Kurz" => "U_23", "uDatum-minus" => 22, "oDatum-minus" => 20, "Urkunde" => 0, "Bronze" => 0, "Silber" => 0, "Gold" => 0),
        8 => array("Name" => "Erwachsene", "Kurz" => "Erw", "uDatum-minus" => 29, "oDatum-minus" => 23, "Urkunde" => 0, "Bronze" => 0, "Silber" => 0, "Gold" => 0),
        9 => array("Name" => "Senioren M30", "Kurz" => "30", "uDatum-minus" => 34, "oDatum-minus" => 30, "Urkunde" => 0, "Bronze" => 0, "Silber" => 0, "Gold" => 0),
        10 => array("Name" => "Senioren M35", "Kurz" => "35", "uDatum-minus" => 39, "oDatum-minus" => 35, "Urkunde" => 0, "Bronze" => 0, "Silber" => 0, "Gold" => 0),
        11 => array("Name" => "Senioren M40", "Kurz" => "40", "uDatum-minus" => 44, "oDatum-minus" => 40, "Urkunde" => 0, "Bronze" => 0, "Silber" => 0, "Gold" => 0),
        12 => array("Name" => "Senioren M45", "Kurz" => "45", "uDatum-minus" => 49, "oDatum-minus" => 45, "Urkunde" => 0, "Bronze" => 0, "Silber" => 0, "Gold" => 0),
        13 => array("Name" => "Senioren M50", "Kurz" => "50", "uDatum-minus" => 54, "oDatum-minus" => 50, "Urkunde" => 0, "Bronze" => 0, "Silber" => 0, "Gold" => 0),
        14 => array("Name" => "Senioren M55", "Kurz" => "55", "uDatum-minus" => 59, "oDatum-minus" => 55, "Urkunde" => 0, "Bronze" => 0, "Silber" => 0, "Gold" => 0),
        15 => array("Name" => "Senioren M60", "Kurz" => "60", "uDatum-minus" => 64, "oDatum-minus" => 60, "Urkunde" => 0, "Bronze" => 0, "Silber" => 0, "Gold" => 0),
        16 => array("Name" => "Senioren M65", "Kurz" => "65", "uDatum-minus" => 69, "oDatum-minus" => 65, "Urkunde" => 0, "Bronze" => 0, "Silber" => 0, "Gold" => 0),
        17 => array("Name" => "Senioren M70", "Kurz" => "70", "uDatum-minus" => 74, "oDatum-minus" => 70, "Urkunde" => 0, "Bronze" => 0, "Silber" => 0, "Gold" => 0),
        18 => array("Name" => "Senioren M75", "Kurz" => "75", "uDatum-minus" => 79, "oDatum-minus" => 75, "Urkunde" => 0, "Bronze" => 0, "Silber" => 0, "Gold" => 0),
        19 => array("Name" => "Senioren M80", "Kurz" => "80", "uDatum-minus" => 84, "oDatum-minus" => 80, "Urkunde" => 0, "Bronze" => 0, "Silber" => 0, "Gold" => 0),
        20 => array("Name" => "Senioren M85", "Kurz" => "85", "uDatum-minus" => 89, "oDatum-minus" => 85, "Urkunde" => 0, "Bronze" => 0, "Silber" => 0, "Gold" => 0),
        21 => array("Name" => "Senioren M90", "Kurz" => "90", "uDatum-minus" => 94, "oDatum-minus" => 90, "Urkunde" => 0, "Bronze" => 0, "Silber" => 0, "Gold" => 0)
    );

    private array $akBezGemPCS = array(
        0 => array("Name" => "Kinder unter 8", "Kurz" => "K_U8", "uDatum-minus" => 8, "oDatum-minus" => 0,"startgeld" => 3, "tpgeld"=> 2, "Urkunde" => 50, "Bronze" => 500, "Silber" => 1200, "Gold" => 3500),
        1 => array("Name" => "Kinder 9 - 10", "Kurz" => "K_9-10", "uDatum-minus" => 10, "oDatum-minus" => 9,"startgeld" => 3, "tpgeld"=> 2, "Urkunde" => 50, "Bronze" => 1000, "Silber" => 2750, "Gold" => 5000),
        2 => array("Name" => "Kinder 11 - 13", "Kurz" => "K_11-13", "uDatum-minus" => 13, "oDatum-minus" => 11,"startgeld" => 3, "tpgeld"=> 2, "Urkunde" => 50, "Bronze" => 2000, "Silber" => 4000, "Gold" => 6500),
        3 => array("Name" => "Jugend 14 - 18", "Kurz" => "J_14-18", "uDatum-minus" => 18, "oDatum-minus" => 14,"startgeld" => 3, "tpgeld"=> 2, "Urkunde" => 50, "Bronze" => 4000, "Silber" => 6500, "Gold" => 9000),
        4 => array("Name" => "Erwachsene 19 - 39", "Kurz" => "E_19-39", "uDatum-minus" => 39, "oDatum-minus" => 19,"startgeld" => 3, "tpgeld"=> 2, "Urkunde" => 50, "Bronze" => 5000, "Silber" => 7500, "Gold" => 10000),
        5 => array("Name" => "Erwachsene 40 - 59", "Kurz" => "E_40-59", "uDatum-minus" => 59, "oDatum-minus" => 40,"startgeld" => 3, "tpgeld"=> 2, "Urkunde" => 50, "Bronze" => 3000, "Silber" => 5500, "Gold" => 7500),
        6 => array("Name" => "Erwachsene 60 - 75", "Kurz" => "E_60-75", "uDatum-minus" => 74, "oDatum-minus" => 60,"startgeld" => 3, "tpgeld"=> 2, "Urkunde" => 50, "Bronze" => 2000, "Silber" => 3500, "Gold" => 5000),
    	7 => array("Name" => "Erwachsene 75 - 100", "Kurz" => "E_76-100", "uDatum-minus" => 100, "oDatum-minus" => 75,"startgeld" => 3, "tpgeld"=> 2, "Urkunde" => 50, "Bronze" => 1000, "Silber" => 2000, "Gold" => 3000)
    );

    public function getAkBezGemDLO2017(): array
    {
        return $this->akBezGemDLO2017;
    }

    public function getAkBezGemPCS(): array
    {
        return $this->akBezGemPCS;
    }

    public function __construct()
    {
        $this->id = null;
        $this->mitgliederList = new ArrayCollection();

    }

    public function setId(?int $id)
    {
        $this->id = $id;

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setAltersklasse(string $altersklasse)
    {
        $this->altersklasse = $altersklasse;
    }

    public function getAltersklasse(): string
    {
        return $this->altersklasse;
    }

    public function setAltersklasseKurz(string $altersklasseKurz)
    {
        $this->altersklasseKurz = $altersklasseKurz;
    }

    public function getAltersklasseKurz(): string
    {
        return $this->altersklasseKurz;
    }

    public function setUDatum(?DateTimeImmutable $uDatum): void
    {
        $this->uDatum = $uDatum;
    }

    public function getUDatum(): ?DateTimeImmutable
    {
        return $this->uDatum;
    }

    public function setODatum(?DateTimeImmutable $oDatum): void
    {
        $this->oDatum = $oDatum;
    }

    public function getODatum(): ?DateTimeImmutable
    {
        return $this->oDatum;
    }

    public function setStartgeld(int $startgeld): void
    {
        $this->startgeld = $startgeld;
    }

    public function getStartAlter(): int
    {
        return $this->startAlter;
    }

    public function setStartAlter(int $startAlter)
    {
        $this->startAlter = $startAlter;
    }

    public function getEndeAlter (): int
    {
        return $this->endeAlter ;
    }

    public function setEndeAlter (int $endeAlter)
    {
        $this->endeAlter = $endeAlter ;
    }

    public function getStartgeld(): float
    {
        return $this->startgeld;
    }

    public function setTpgeld(float $tpgeld)
    {
        $this->tpgeld = $tpgeld;
    }

    public function getTpgeld(): float 
    {
        return $this->tpgeld;
    }

    public function setWertungsschluessel(?float $wertungsschluessel)
    {
        $this->wertungsschluessel = $wertungsschluessel;
    }

    public function getWertungsschluessel(): ?float
    {
        return $this->wertungsschluessel;
    }

    public function setRekord(?int $rekord)
    {
        $this->rekord = $rekord;
    }

    public function getRekord(): ?int
    {
        return $this->rekord;
    }

    public function setUrkunde(?int $urkunde)
    {
        $this->urkunde = $urkunde;
    }

    public function getUrkunde(): ?int
    {
        return $this->urkunde;
    }

    public function setBronze(?int $bronze)
    {
        $this->bronze = $bronze;
    }

    public function getBronze(): ?int
    {
        return $this->bronze;
    }

    public function setSilber(?int $silber)
    {
        $this->silber = $silber;
    }

    public function getSilber(): ?int
    {
        return $this->silber;
    }

    public function setGold(?int $gold)
    {
        $this->gold = $gold;
    }

    public function getGold(): ?int
    {
        return $this->gold;
    }

    public function getMitgliederList(): Collection
    {
        return $this->mitgliederList;
    }

    function __toString()
    {
        return $this->getAltersklasse();
    }


}

