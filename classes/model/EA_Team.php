<?php

namespace CharitySwimRun\classes\model;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'mannschaft')]
class EA_Team
{
    #[ORM\Column(type: Types::INTEGER, name: "MannschaftId")]
    #[ORM\GeneratedValue]
    #[ORM\Id]
    private ?int $id = null;

    #[ORM\Column(type: Types::INTEGER, name: "Startnummer")]
    private ?int $startnummer = null;
    
    #[ORM\Column(type: Types::STRING, name: "Mannschaft")]
    private string $mannschaft = "";

    #[ORM\Column(type: Types::FLOAT, name: "Punkte")]
    private int $punkte = 0;

    #[ORM\Column(type: Types::STRING, name: "Ver_name")]
    private ?string $ver_name = null;

    #[ORM\Column(type: Types::STRING, name: "Ver_vorname")]
    private ?string $ver_vorname = null;

    #[ORM\Column(type: Types::STRING, name: "Ver_mail")]
    private ?string $ver_mail = null;

    #[ORM\Column(type: Types::STRING, name: "Kennwort")]
    private ?string $kennwort = "nichtBenutzt";

    #[ORM\ManyToOne(targetEntity: EA_TeamCategory::class, inversedBy:'mannschaftListNeu')]
    #[ORM\JoinColumn(name: 'Mannschaftskategorie', referencedColumnName: 'MannschaftskategorieId',nullable:true)]
    private ?EA_TeamCategory $mannschaftskategorie = null;

    private int $gesamtimpulseCache = 0;
    private float $gesamtGeldCache = 0;
    private int $gesamtStreckenartCache = 0;
    private int $gesamtMeterCache = 0;

    #[ORM\OneToMany(targetEntity: EA_Starter::class, mappedBy: 'mannschaft')]
    private Collection $mitgliederList;

    public function __construct()
    {
        $this->mitgliederList = new ArrayCollection();
        $this->mannschaftskategorie = new EA_TeamCategory();
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getStartnummer(): ?int
    {
        return $this->startnummer;
    }

    public function setStartnummer(int $startnummer)
    {
        $this->startnummer = $startnummer;
    }

    public function getMannschaft(): string
    {
        return $this->mannschaft;
    }

    public function setMannschaft($mannschaft)
    {
        $this->mannschaft = $mannschaft;
    }

    public function getPunkte(): int
    {
        return $this->punkte;
    }

    public function setPunkte($punkte)
    {
        $this->punkte = $punkte;
    }

    public function getVer_name(): ?string
    {
        return $this->ver_name;
    }

    public function setVer_name(?string $ver_name)
    {
        $this->ver_name = $ver_name;
    }

    public function getVer_vorname(): ?string
    {
        return $this->ver_vorname;
    }

    public function setVer_vorname(?string $ver_vorname)
    {
        $this->ver_vorname = $ver_vorname;
    }

    public function getVer_mail(): ?string
    {
        return $this->ver_mail;
    }

    public function setVer_mail(?string $ver_mail)
    {
        $this->ver_mail = $ver_mail;
    }

    public function getMannschaftskategorie(): ?EA_TeamCategory
    {
        return $this->mannschaftskategorie;
    }

    public function setMannschaftskategorie(?EA_TeamCategory $mannschaftskategorie)
    {
        $this->mannschaftskategorie = $mannschaftskategorie;
    }

    public function getKennwort(): ?string
    {
        return $this->kennwort;
    }

    public function setKennwort(?string $kennwort)
    {
        $this->kennwort = $kennwort;
    }

    public function getMitgliederList(): Collection
    {
        return $this->mitgliederList;
    }


    public function getGesamtImpulse():int
    {
        if($this->gesamtimpulseCache === 0){
            foreach($this->getMitgliederList() as $teilnehmer){
                $this->gesamtimpulseCache += $teilnehmer->getImpulse();
                $this->gesamtGeldCache += $teilnehmer->getGeld();
                $this->gesamtStreckenartCache += $teilnehmer->getStreckenart();
                $this->gesamtMeterCache += $teilnehmer->getMeter();
            }
        }

        return $this->gesamtimpulseCache;
    }

    public function getGesamtGeld(): float
    {
        return $this->gesamtGeldCache;

    }

    public function getGesamtMeter(): int
    {
        return $this->gesamtMeterCache;

    }

    public function getGesamtStreckenart(): int
    {
        return $this->gesamtStreckenartCache;

    }



    public function __toString()
    {
        $content = "<b>Mannschaft:</b> " . $this->getMannschaft() . ", ";
        $content .= "<b>StNr:</b> " . $this->getStartnummer() . ", ";
        $content .= "<b>Verantwortlicher:</b> " . $this->getVer_vorname() . " " . $this->getVer_name() . ", ";
        $content .= "<b>E-Mail:</b> " . $this->getVer_mail();
        return $content;
    }
}