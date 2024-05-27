<?php

namespace CharitySwimRun\classes\model;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

//This class is only need for the admin's project

#[ORM\Entity]
#[ORM\Table(name: 'cache')]
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
    
    #[ORM\Column(type: Types::INTEGER,nullable:false,options:["default"=>0])]
    private int $Leser = 0;
}