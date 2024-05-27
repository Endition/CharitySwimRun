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
    
    private array $mannschaftList = [];

    #[ORM\OneToMany(targetEntity: EA_Team::class, mappedBy: 'mannschaftskategorie')]
    private Collection $mannschaftListNeu;


    public function __construct()
    {
        $this->mannschaftListNeu = new ArrayCollection();
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

    public function getMannschaftList(): array
    {
        return $this->mannschaftList;
    }

    public function setMannschaftList(array $mannschaftList)
    {
        $this->mannschaftList = $mannschaftList;
    }

    public function addMannschaft($mannschaft)
    {
        $this->mannschaftList[] = $mannschaft;
    }

    public function getMannschaftListAnzahl(): int
    {
        return count($this->mannschaftList);
    }

}