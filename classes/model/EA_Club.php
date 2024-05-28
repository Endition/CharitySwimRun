<?php

namespace CharitySwimRun\classes\model;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
#[ORM\Table(name: 'verein')]
class EA_Club
{


    #[ORM\Column(type: Types::INTEGER,name:"VereinId")]
    #[ORM\GeneratedValue]
    #[ORM\Id]
    private ?int $id = null;
    
    #[ORM\Column(type: Types::STRING,name:"Verein")]
    private string $verein = "";

    #[ORM\OneToMany(targetEntity: EA_Starter::class, mappedBy: 'verein')]
    #[ORM\OrderBy(["name" => "ASC"])]
    private Collection $mitgliederList;

    private int $gesamtimpulseCache = 0;
    private float $gesamtGeldCache = 0;
    private int $gesamtStreckenartCache = 0;
    private int $gesamtMeterCache = 0;


    public function __construct()
    {
        $this->mitgliederList = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id)
    {
        $this->id = $id;
    }

    public function getVerein(): string
    {
        return $this->verein;
    }

    public function setVerein(string $verein): void
    {
        $this->verein = $verein;
    }

    public function getMitgliederList(): Collection
    {
        return $this->mitgliederList;
    }

    public function setMitgliederList(Collection $mitgliederList)
    {
        $this->mitgliederList = $mitgliederList;
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
        return "<b>Verein:</b> " . $this->getVerein() . ", ";
    }

}