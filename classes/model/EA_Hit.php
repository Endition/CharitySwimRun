<?php

namespace CharitySwimRun\classes\model;

use CharitySwimRun\classes\helper\EA_Helper;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
#[ORM\Table(name: 'log')]
#[ORM\Index(name: "TeilnehmerId", columns: ["TeilnehmerId"])]
#[ORM\Index(name: "TransponderId", columns: ["TransponderId"])]
#[ORM\Index(name: "Leser", columns: ["Leser"])]
#[ORM\Index(name: "berechnet", columns: ["berechnet"])]
#[ORM\Index(name: "geloescht", columns: ["geloescht"])]

class EA_Hit
{
    #[ORM\Column(type: Types::INTEGER,name:"ImpulsId")]
    #[ORM\GeneratedValue]
    #[ORM\Id]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: EA_Starter::class)]
    #[ORM\JoinColumn(name: 'TeilnehmerId', referencedColumnName: 'id',nullable:true,options:["default"=>null])]
    private ?EA_Starter $teilnehmer = null;

    #[ORM\Column(type: Types::INTEGER,name:"TransponderId",nullable:true,options:["default"=>null])]
    private ?int $transponderId = null;

    #[ORM\Column(type: Types::INTEGER,name:"Timestamp")]
    private ?int $timestamp = null;
    private ?int $rundenzeit = null;
    private ?int $gesamtzeit = null;

    #[ORM\Column(type: Types::INTEGER,name:"Leser")]
    private int $leser = 0;

    #[ORM\Column(type: Types::BOOLEAN,name:"berechnet")]
    private bool $berechnet = false;

    #[ORM\Column(type: Types::BOOLEAN,name:"geloescht")]
    private bool $geloescht = false;

    public function __construct()
    {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getTransponderId(): ?int
    {
        return $this->transponderId;
    }

    public function getTeilnehmer(): ?EA_Starter
    {
        return $this->teilnehmer;
    }

    public function setTeilnehmer(?EA_Starter $teilnehmer): void
    {
        $this->teilnehmer = $teilnehmer;
    }

    public function setTransponderId(?int $transponderId): void
    {
        $this->transponderId = $transponderId;
    }

    public function getTimestamp(?string $format = null): ?string
    {
        if ($format !== null) {
            return date($format, $this->timestamp);
        } else {
            return $this->timestamp;
        }
    }

    public function setTimestamp(?int $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    public function getRundenzeit(string $format = "ts"): string
    {
        if($this->rundenzeit === null){
            $this->rundenzeit = 0;
        }
        if ($format === "ts") {
            return $this->rundenzeit === null ? 0 : $this->rundenzeit;
        } elseif ($format === "H:i:s") {
            return $this->rundenzeit === null ? 0 : EA_Helper::FormatterRundenzeit($this->rundenzeit);
        }else{
            return "";
        }
    }

    public function setRundenzeit(?int $rundenzeit): void
    {
        $this->rundenzeit = $rundenzeit;
    }

    public function getGesamtzeit(string $format = "ts"): string
    {
        if ($format === "ts") {
            return $this->gesamtzeit === null ? 0 : $this->gesamtzeit;
        } elseif ($format === "H:i:s") {
            return $this->gesamtzeit === null ? 0 : EA_Helper::FormatterRundenzeit($this->gesamtzeit);
        }else{
            return "";
        }
    }

    public function setGesamtzeit(?int $gesamtzeit)
    {
        $this->gesamtzeit = $gesamtzeit;
    }

    public function getLeser(): int
    {
        return $this->leser;
    }

    public function setLeser(int $leser)
    {
        $this->leser = $leser;
    }

    public function getBerechnet(): bool
    {
        return $this->berechnet;
    }

    public function setBerechnet(bool $berechnet)
    {
        $this->berechnet = $berechnet;
    }

    public function getGeloescht(): bool
    {
        return $this->geloescht;
    }

    public function setGeloescht(bool $geloescht)
    {
        $this->geloescht = $geloescht;
    }
 
}