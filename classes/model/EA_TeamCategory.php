<?php

namespace CharitySwimRun\classes\model;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;



#[ORM\Entity]
#[ORM\Table(name: 'mannschaft_kategorien')]
class EA_TeamCategory
{
    #[ORM\Column(type: Types::INTEGER, name: "MannschaftskategorieId")]
    #[ORM\GeneratedValue]
    #[ORM\Id]
    private ?int $id = null;
    

    #[ORM\Column(type: Types::STRING, name: "Mannschaftskategorie")]
    private string $mannschaftskategorie = "";
    
    #[ORM\OneToMany(targetEntity: EA_Team::class, mappedBy: 'mannschaftskategorie')]
    private Collection $mannschaftList;


    public function __construct()
    {
        $this->mannschaftList = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id)
    {
        $this->id = $id;
    }

    public function getMannschaftskategorie(): string
    {
        return $this->mannschaftskategorie;
    }

    public function setMannschaftskategorie($verein)
    {
        $this->mannschaftskategorie = $verein;
    }

    public function getMannschaftList(): Collection
    {
        return $this->mannschaftList;
    }


}