<?php

namespace CharitySwimRun\classes\model;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
#[ORM\Table(name: 'unternehmen')]
class EA_Company
{


    #[ORM\Column(type: Types::INTEGER,name:"UnternehmenId")]
    #[ORM\GeneratedValue]
    #[ORM\Id]
    private ?int $id = null;
    
    #[ORM\Column(type: Types::STRING,name:"Unternehmen")]
    private string $unternehmen = "";

    #[ORM\OneToMany(targetEntity: EA_Starter::class, mappedBy: 'unternehmen')]
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

    public function getUnternehmen(): string
    {
        return $this->unternehmen;
    }

    public function setUnternehmen(string $unternehmen): void
    {
        $this->unternehmen = $unternehmen;
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
        return $this->getUnternehmen();
    }

}