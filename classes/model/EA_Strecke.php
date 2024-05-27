<?php
namespace EndeAuswertung\classes\model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: 'strecken')]
class EA_Strecke
{
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    #[ORM\Id]
    private ?int $id = null;
    
 
    #[ORM\Column(type: Types::STRING, name:"bezeichnungLang")]
    private string $bezLang = "";

    #[ORM\Column(type: Types::STRING, name:"bezeichnungKurz")]
    private string $bezKurz = "";
    
    #[ORM\OneToMany(targetEntity: EA_Teilnehmer::class, mappedBy: 'strecke')]
    private Collection $mitgliederList;

    public function __construct()
    {
        $this->mitgliederList = new ArrayCollection();
    }

    public function setId(?int $id=null)
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBezLang(): string
    {
        return $this->bezLang;
    }

    public function setBezLang(string $bezLang=""): void
    {
        $this->bezLang = $bezLang;
    }

    public function getBezKurz(): string
    {
        return $this->bezKurz;
    }

    public function setBezKurz(string $bezKurz = ""): void
    {
        $this->bezKurz = $bezKurz;
    }
    
    public function getMitgliederList(): Collection
    {
        return $this->mitgliederList;
    }

    public function __toString()
    {
        return $this->getBezLang();
    }

}