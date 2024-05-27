<?php

namespace CharitySwimRun\classes\model;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;


#[ORM\Entity]
#[ORM\Table(name: 'specialEvaluation')]
class EA_SpecialEvaluation
{
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    #[ORM\Id]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING)]
    private string $name = "";

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE,nullable:true,options:["default"=>null])]
    private DateTimeImmutable $start;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE,nullable:true,options:["default"=>null])]
    private DateTimeImmutable $end;

    #[ORM\ManyToOne(targetEntity: EA_Distance::class)]
    #[ORM\JoinColumn(name: 'strecke', referencedColumnName: 'id',nullable:true,options:["default"=>null])]
    private ?EA_Distance $strecke = null;

    #[ORM\ManyToOne(targetEntity: EA_AgeGroup::class)]
    #[ORM\JoinColumn(name: 'altersklasse', referencedColumnName: 'Id',nullable:true,options:["default"=>null])]
    private ?EA_AgeGroup $altersklasse = null;

    #[ORM\Column(type: Types::STRING,nullable:true,options:["default"=>null])]
    private ?string $geschlecht = null;

    #[ORM\Column(type: Types::INTEGER,nullable:true,options:["default"=>null])]
    private ?int $leser = null;

    public function __construct()
    {
        $this->start = new DateTimeImmutable();
        $this->end = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getStart(): DateTimeImmutable
    {
        return $this->start;
    }


    public function setStart(DateTimeImmutable $start): void
    {
        $this->start = $start;
    }


    public function getEnd(): DateTimeImmutable
    {
        return $this->end;
    }
    public function setEnd(DateTimeImmutable $end): void
    {
        $this->end = $end;
    }

    public function getStrecke(): ?EA_Distance
    {
        return $this->strecke;
    }


    public function setStrecke(?EA_Distance $strecke): void
    {
        $this->strecke = $strecke;
    }

    public function getGeschlecht(): ?string
    {
        return $this->geschlecht;
    }

    public function setGeschlecht(?string $geschlecht): void
    {
        $this->geschlecht = $geschlecht;
    }

    public function getLeser(): ?int 
    {
        return $this->leser;
    }

    public function setLeser(?int $leser): void
    {
        $this->leser = $leser;

    }

    public function getAltersklasse(): ?EA_AgeGroup 
    {
        return $this->altersklasse;
    }

    public function setAltersklasse(?EA_AgeGroup $altersklasse) : void
    {
        $this->altersklasse = $altersklasse;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}