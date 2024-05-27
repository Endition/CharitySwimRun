<?php

namespace CharitySwimRun\classes\model;

use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use CharitySwimRun\classes\helper\EA_Helper;
use CharitySwimRun\classes\model\EA_AgeGroup;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
#[ORM\Table(name: 'teilnehmer')]
#[ORM\Index(name: "Transponder", columns: ["Transponder"])]
#[ORM\Index(name: "Startnummer", columns: ["Startnummer"])]
#[ORM\Index(name: "Verein", columns: ["Verein"])]
#[ORM\Index(name: "Strecke", columns: ["Strecke"])]
#[ORM\Index(name: "Mannschaft", columns: ["Mannschaft"])]
class EA_Starter
{
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    #[ORM\Id]
    private ?int $id = null;

    #[ORM\Column(type: Types::INTEGER,name:'Startnummer',nullable:true,options:["default"=>null])]
    private ?int $startnummer = null;

    #[ORM\Column(type: Types::INTEGER,name:'Transponder',nullable:true,options:["default"=>null])]
    private ?int $transponder = null;

    #[ORM\Column(type: Types::INTEGER,name:'Status')]
    private int $status = self::STATUS_ANGEMELDET;

    #[ORM\Column(type: Types::INTEGER,name:'Gesamtplatz')]
    private int $gesamtplatz = 99999;

    #[ORM\Column(type: Types::INTEGER,name:'Streckenplatz')]
    private int $streckenplatz = 99999;
    
    #[ORM\Column(type: Types::INTEGER,name:'AKPlatz')]
    private int $akplatz = 99999;

    #[ORM\Column(type: Types::STRING,name:'Name')]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING,name:'Vorname')]
    private ?string $vorname = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE,name:'Geburtsdatum',nullable:true,options:["default"=>null])]
    private DateTimeImmutable $geburtsdatum;

    #[ORM\Column(type: Types::STRING,name:'Geschlecht')]
    private string $geschlecht = self::GESCHLECHT_M;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE,name:'Startzeit',nullable:true,options:["default"=>null])]
    private ?DateTimeImmutable $startzeit = null;

    #[ORM\ManyToOne(targetEntity: EA_AgeGroup::class)]
    #[ORM\JoinColumn(name: 'Altersklasse', referencedColumnName: 'Id',nullable:true,options:["default"=>null])]
    private ?EA_AgeGroup $altersklasse = null;

    #[ORM\ManyToOne(targetEntity: EA_Team::class)]
    #[ORM\JoinColumn(name: 'Mannschaft', referencedColumnName: 'MannschaftId',nullable:true,options:["default"=>null])]
    private ?EA_Team $mannschaft = null;

    #[ORM\ManyToOne(targetEntity: EA_Club::class)]
    #[ORM\JoinColumn(name: 'Verein', referencedColumnName: 'VereinId',nullable:true,options:["default"=>null])]
    private ?EA_Club $verein = null;

    #[ORM\ManyToOne(targetEntity: EA_Distance::class)]
    #[ORM\JoinColumn(name: 'Strecke', referencedColumnName: 'id',nullable:true,options:["default"=>null])]
    private ?EA_Distance $strecke = null;

    #[ORM\Column(type: Types::INTEGER,name:'Startgruppe')]
    private int $startgruppe = 0;

    #[ORM\Column(type: Types::STRING,name:'Mail',nullable:true,options:["default"=>null])]
    private ?string $mail = null;

    #[ORM\Column(type: Types::STRING,name:'PLZ',nullable:true,options:["default"=>null])]
    private ?int $plz = null;

    #[ORM\Column(type: Types::STRING,name:'Wohnort',nullable:true,options:["default"=>null])]
    private ?string $wohnort = null;

    #[ORM\Column(type: Types::STRING,name:'Strasse',nullable:true,options:["default"=>null])]
    private ?string $strasse = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE,columnDefinition:"DATETIME on update CURRENT_TIMESTAMP")]
    private ?DateTimeImmutable $lastUpdated = null;
    
    #[ORM\OneToMany(targetEntity: EA_Hit::class, mappedBy: 'teilnehmer',fetch: 'EXTRA_LAZY')]
    private Collection $impulsList;

    //Trick to make the Konfiguration available in every attendence
    #[ORM\ManyToOne(targetEntity: EA_Configuration::class)]
    #[ORM\JoinColumn(name: 'konfigurationId', referencedColumnName: 'id',nullable:false,options:["default"=>1])]
    private EA_Configuration $konfiguration;


    private bool $rundenzeitenBerechnet = false;

    #[ORM\Column(type: Types::INTEGER)]
    private int $impulseCache = 0;

    private int $impulseSpecialEvaluationCache = 0;
    private ?Collection $gueltigeImpulseListCache = null;

    public const GESCHLECHT_M = "M";
    public const GESCHLECHT_W = "W";
    public const GESCHLECHT_D = "D";

    public const GESCHLECHT_LIST = [self::GESCHLECHT_M => "männlich", self::GESCHLECHT_W => "weiblich", self::GESCHLECHT_D => "divers"];
    public const GESCHLECHT_LIST_KURZ = [self::GESCHLECHT_M => "M", self::GESCHLECHT_W => "W", self::GESCHLECHT_D => "D"];


    public const STATUS_ANGEMELDET = 10;
    public const STATUS_BEZAHLT = 20;
    public const STATUS_STARTUNTERLAGEN_ABHEHOLT = 30;
    public const STATUS_GESTARTET= 50;
    public const STATUS_AUF_DER_STRECKE = 70;
    public const STATUS_GUELTIGE_BUCHUNG = 90;
    public const STATUS_TRANSPORTER_ZURUECKGEGEBENE = 99;

    public const STATUS_LIST = [
            self::STATUS_ANGEMELDET => "angemeldet",
            self::STATUS_BEZAHLT  => "bezahlt",
            self::STATUS_STARTUNTERLAGEN_ABHEHOLT  => "Startunterlagen abgeholt",
            self::STATUS_GESTARTET  => "gestartet",
            self::STATUS_AUF_DER_STRECKE  => "auf der Strecke",
            self::STATUS_GUELTIGE_BUCHUNG  => "gültige Buchung",
            self::STATUS_TRANSPORTER_ZURUECKGEGEBENE  => "Transponder zurückgegeben"
    ];

    public function __construct()
    {
        $this->geburtsdatum = new DateTimeImmutable();
        $this->impulsList = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id)
    {
        $this->id = $id;
    }

    public function getStartnummer(): ?int
    {
        return $this->startnummer;
    }

    public function setStartnummer($startnummer)
    {
        $this->startnummer = $startnummer;
    }

    public function getTransponder(): ?int
    {
        return $this->transponder;
    }

    public function setTransponder($transponder)
    {
        $this->transponder = $transponder;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;

    }

    public function getStreckenplatz(): int
    {
        return $this->streckenplatz;
    }

    public function setStreckenplatz($streckenplatz)
    {
        $this->streckenplatz = $streckenplatz;

    }

    public function getGesamtplatz(): int
    {
        return $this->gesamtplatz;
    }

    public function setGesamtplatz($gesamtplatz)
    {
        $this->gesamtplatz = $gesamtplatz;

    }

    public function getAkplatz(): int
    {
        return $this->akplatz;
    }

    public function setAkplatz($akplatz)
    {
        $this->akplatz = $akplatz;

    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

    }

    public function getVorname(): ?string
    {
        return $this->vorname;
    }

    public function setVorname($vorname)
    {
        $this->vorname = $vorname;

    }

    public function getGesamtname(): string
    {
        return $this->name . ", " . $this->vorname;
    }

    public function getGeburtsdatum(): DateTimeImmutable
    {
        return $this->geburtsdatum;
    }

    public function setGeburtsdatum(DateTimeImmutable $geburtsdatum): void
    {
        $this->geburtsdatum = $geburtsdatum;

    }

    public function getGeschlecht(): string
    {
        return $this->geschlecht;
    }

    public function setGeschlecht($geschlecht)
    {
        $this->geschlecht = $geschlecht;

    }

    public function getStartzeit(): ?DateTimeImmutable
    {
        return $this->startzeit;
    }

    public function setStartzeit(?DateTimeImmutable $startzeit)
    {
        $this->startzeit = $startzeit;

    }

    public function getAltersklasse(): EA_AgeGroup
    {
        if ($this->altersklasse !== null) {
            return $this->altersklasse;
        } else {
            return new EA_AgeGroup();
        }
    }

    public function setAltersklasse(?EA_AgeGroup $altersklasse)
    {
        $this->altersklasse = $altersklasse;

    }

    public function getMannschaft(): EA_Team
    {
        if ($this->mannschaft !== null) {
            return $this->mannschaft;
        } else {
            return new EA_Team();
        }
    }

    public function setMannschaft(?EA_Team $mannschaft)
    {
        $this->mannschaft = $mannschaft;

    }

    public function getVerein(): EA_Club
    {
        if ($this->verein !==null) {
            return $this->verein;
        } else {
            return new EA_Club();
        }
    }

    public function setVerein(?EA_Club$verein)
    {
        $this->verein = $verein;

    }

    public function getStrecke(): EA_Distance
    {
        if ($this->strecke !== null) {
            return $this->strecke;
        } else {
            return new EA_Distance();
        }
    }

    public function setStrecke(?EA_Distance $strecke)
    {
        $this->strecke = $strecke;

    }

    public function getStartgruppe(): ?int
    {
        return $this->startgruppe;
    }

    public function setStartgruppe(?int $startgruppe)
    {
        $this->startgruppe = $startgruppe;

    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail)
    {
        $this->mail = $mail;

    }

    public function getPlz(): ?int
    {
        return $this->plz;
    }

    public function setPlz(?int $plz)
    {
        $this->plz = $plz;

    }

    public function getWohnort(): ?string
    {
        return $this->wohnort;
    }

    public function setWohnort(?string $wohnort): void
    {
        $this->wohnort = $wohnort;

    }

    public function getStrasse(): ?string
    {
        return $this->strasse;
    }

    public function setStrasse(?string $strasse): void
    {
        $this->strasse = $strasse;

    }

    public function getLetzteBuchung(string $format = "d.m.Y H:i:s"): string
    {
        if($this->getImpulseListGueltige()->last()){
            return date($format, $this->getImpulseListGueltige()->last()->getTimestamp());
        }
        return "";
    }


    public function getImpulseListGueltige(bool $berechneRunden = false): Collection
    {
        if($this->getStartzeit() === null){
            return new ArrayCollection();
        }
        if($this->gueltigeImpulseListCache !== null){
            return $this->gueltigeImpulseListCache;
        }
        $criteria = Criteria::create();
        $criteria->where(
            Criteria::expr()->andX(
                Criteria::expr()->eq('geloescht', false),
                Criteria::expr()->gte('timestamp', $this->getStartzeit()->getTimestamp())
            ));        
        $this->gueltigeImpulseListCache = $this->impulsList->matching($criteria);
        if($berechneRunden === true){
            $this->calculateRundenzeiten($this->gueltigeImpulseListCache);
        }
        
        return $this->gueltigeImpulseListCache;
    }

    public function getImpulseSonderwertung(EA_SpecialEvaluation $specialEvaluation): Collection
    {
        if($this->getStartzeit() === null){
            return new ArrayCollection();
        }
        $criteria = Criteria::create();
        $criteria->where(
            Criteria::expr()->andX(
                Criteria::expr()->eq('geloescht', false),
                Criteria::expr()->gte('timestamp', $specialEvaluation->getStart()->getTimestamp()),
                Criteria::expr()->lte('timestamp', $specialEvaluation->getEnd()->getTimestamp()),
                Criteria::expr()->eq('leser', $specialEvaluation->getLeser())
            ));        
        $gueltigeimpulseSpecialEvaluationList = $this->impulsList->matching($criteria);

        $this->impulseSpecialEvaluationCache = $gueltigeimpulseSpecialEvaluationList->count();
        return $gueltigeimpulseSpecialEvaluationList; 
    }

    public function calculateRundenzeiten(Collection $impulseList): void
    {
        //nicht erneut
        if($this->rundenzeitenBerechnet === true){
            return;
        }
        $weiche = false;
        $rundenzeit = 999999999999999999;
        $timestamp_alt = 0;
        $startzeit = $this->getStartzeit()->getTimestamp();
        if ($impulseList->count() > 0) {
            foreach ($impulseList as $impuls) {
                //nur beim ersten Durchlauf durchlaufen
                if ($weiche === false) {
                    $timestamp_alt = $startzeit;
                    $weiche = true;
                }
                //gesamtzeit für diesen impuls berechnen
                $gesamtzeit = $impuls->getTimestamp() - $startzeit;
                $impuls->setGesamtzeit($gesamtzeit);
                //rundenzeit für diesen Impuls berechnen
                $rundenzeit = $impuls->getTimestamp() - $timestamp_alt;
                $impuls->setRundenzeit($rundenzeit);
                $timestamp_alt = $impuls->getTimestamp();
            }
            $this->rundenzeitenBerechnet = true;
        }
    }


    public function getImpulse(): int
    {
        return $this->impulseCache;
    }
    
    public function getMeter(): int
    {
        return round($this->getImpulse() * $this->konfiguration->getRundenlaenge(), 2);
    }

    public function getGeld(): float
    {
        return round($this->getMeter() * $this->konfiguration->getEuroprometer(), 2);
    }

    public function getStreckenart(): int
    {
        return  $this->getImpulse() * $this->konfiguration->getFaktor();
    }

    public function getMeterSonderwertung(): int
    {
        return round($this->impulseSpecialEvaluationCache * $this->konfiguration->getRundenlaenge(), 2);
    }

    public function getGeldSonderwertung(): float
    {
        return round($this->getMeterSonderwertung() * $this->konfiguration->getEuroprometer(), 2);
    }

    public function getStreckenartSonderwertung(): int
    {
        return  $this->impulseSpecialEvaluationCache * $this->konfiguration->getFaktor();
    }

    public function getStartgeld(): float
    {
        if ($this->altersklasse !== null) {
            return $this->altersklasse->getStartgeld();
        } else {
            return 0;
        }
    }

    public function getTranspondergeld(): float
    {
        if ($this->altersklasse !== null) {
            return $this->altersklasse->getTpgeld();;
        } else {
            return 0;
        }
    }


    public function getGesamtzeit(bool $formatiert = true)
    {
        if($this->getLetzteBuchung("U") > 0 && $this->getStartzeit()->getTimestamp() > 0){
           $gesamtzeit = $this->getLetzteBuchung("U") - $this->getStartzeit()->getTimestamp();
            if ($gesamtzeit <= 0) {
                $gesamtzeit = 0;
            }
            if ($formatiert === null) {
                return $gesamtzeit;
            } else {
                return EA_Helper::FormatterRundenzeit($gesamtzeit);
            }        
        }
        return "00:00:00";
    }

    public function getWertung(string $format = "kurz"): string
    {
        $wertung = $this->calculateWertung();
        if ($wertung === "U") {
            return ($format === "lang") ? '<i class="fa fa-file"></i> Urkunde' : "U";
        }
        if ($wertung === "B") {
            return ($format === "lang") ? '<i class="fa fa-trophy" style="color:bronze;"></i> Bronze' : "B";
        }
        if ($wertung === "S") {
            return ($format === "lang") ? '<i class="fa fa-trophy" style="color:silver;"></i> Silber' : "S";
        }
        if ($wertung === "G") {
            return ($format === "lang") ? '<i class="fa fa-trophy" style="color:gold;"></i> Gold' : "G";
        }
        return "";
    }

    private function calculateWertung(): string
    {
        if ($this->altersklasse !== null) {
            if ($this->getMeter()  >= $this->altersklasse->getGold()) {
                return "G";
            }
            if ($this->getMeter()  >= $this->altersklasse->getSilber()) {
                return "S";
            }
            if ($this->getMeter()  >= $this->altersklasse->getBronze()) {
                return "B";
            }
            if ($this->getMeter() >= $this->altersklasse->getUrkunde()) {
                return "U";
            }
        }
        return "";
    }

    public function getNaechsteWertung(): string
    {
        $wertung = $this->calculateWertung();
        if ($wertung === "U") {
            return $this->altersklasse->getBronze() - $this->getMeter() . "m";
        }
        if ($wertung === "B") {
            return $this->altersklasse->getSilber() - $this->getMeter() . "m";
        }
        if ($wertung === "S") {
            return $this->altersklasse->getGold() - $this->getMeter() . "m";
        }
        if ($wertung === "G") {
            return "0m";
        }
        return "0m";
    }
    
    public function getNaechsteWertungStreckenart($rundenlaenge): int
    {
        $wertung = $this->calculateWertung();
        if ($wertung === "U") {
            $meterZurNaechstenWertung = $this->altersklasse->getBronze() - $this->getMeter() ;
            return $meterZurNaechstenWertung/$rundenlaenge;   
        }
        if ($wertung === "B") {
             $meterZurNaechstenWertung = $this->altersklasse->getSilber() - $this->getMeter() ;
            return $meterZurNaechstenWertung/$rundenlaenge;  
        }
        if ($wertung === "S") {
            $meterZurNaechstenWertung = $this->altersklasse->getGold() - $this->getMeter() ;
            return $meterZurNaechstenWertung/$rundenlaenge;  
        }
        if ($wertung === "G") {
            return 0;
        }
        return 0;
    }

    public function setKonfiguration(EA_Configuration $konfiguration): void
    {
        $this->konfiguration = $konfiguration;
    }

    public function __toString()
    {
        $content = "<b>Teilnehmer:</b> " . $this->getGesamtname() . ", ";
        $content .= "<b>Geschlecht:</b> " . $this->getGeschlecht() . ", ";
        $content .= "<b>StNr:</b> " . $this->getStartnummer() . ", ";
        $content .= "<b>TP:</b> " . $this->getTransponder() . ", ";
        $content .= "<b>Geburtsdatum:</b> " . $this->getGeburtsdatum()->format('d.m.Y') . ", ";
        $content .= "<b>Strecke:</b> " . $this->getStrecke()->getBezLang() . "";
        return $content;
    }

}


