<?php

namespace CharitySwimRun\classes\model;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

//This class is only need for the admin's project

#[ORM\Entity]
#[ORM\Table(name: 'cache', options: ['charset' => 'utf8mb4', 'collate' => 'utf8mb4_unicode_ci'])]
#[ORM\Index(name: "verarbeitet", columns: ["verarbeitet"])]
#[ORM\Index(name: "Leser", columns: ["Leser"])]
#[ORM\Index(name: "u1", columns: ["Transponderschluessel","Buchungszeit"])]

class EA_Cache
{
    #[ORM\Column(type: Types::INTEGER,name:"Id")]
    #[ORM\GeneratedValue]
    #[ORM\Id]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING,nullable:true,options:["default"=>null])]
    private ?string $Transponderschluessel = null;
    
    #[ORM\Column(type: Types::INTEGER,nullable:true,options:["default"=>null])]
    private ?int $Buchungszeit = null;
    
    #[ORM\Column(type: Types::BOOLEAN,nullable:true,options:["default"=>null])]
    private ?bool $verarbeitet = null;
    
    #[ORM\Column(type: Types::INTEGER,name:"Leser",nullable:false,options:["default"=>0])]
    private int $Leser = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTransponderschluessel(): ?string
    {
        return $this->Transponderschluessel;
    }

    public function setTransponderschluessel(?string $Transponderschluessel): void
    {
        $this->Transponderschluessel = $Transponderschluessel;
    }

    public function getBuchungszeit(): ?int
    {
        return $this->Buchungszeit;
    }

    public function setBuchungszeit(?int $Buchungszeit): void
    {
        $this->Buchungszeit = $Buchungszeit;
    }

    public function getVerarbeitet(): ?bool
    {
        return $this->verarbeitet;
    }

    public function setVerarbeitet(?bool $verarbeitet): void
    {
        $this->verarbeitet = $verarbeitet;
    }

    public function getLeser(): int
    {
        return $this->Leser;
    }

    public function setLeser(int $Leser): void
    {
        $this->Leser = $Leser;
    }
}