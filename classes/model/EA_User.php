<?php

namespace EndeAuswertung\classes\model;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class EA_User
{
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    #[ORM\Id]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING)]

    private string $username;
    #[ORM\Column(type: Types::STRING)]

    private string $passwordHash;

    #[ORM\Column(type: Types::INTEGER)]
    private int $userroleId = self::USERROLE_PUBLIC;

    public const USERROLE_ADMIN = 1;
    public const USERROLE_ANMELDUNG = 10;
    public const USERROLE_REMOTE_DISPLAY = 50;
    public const USERROLE_PUBLIC = 100;

    public CONST USERROLE_LIST = [
        self::USERROLE_ADMIN => "Admin",
        self::USERROLE_ANMELDUNG => "Mitarbeiter in der Anmeldung",
        self::USERROLE_REMOTE_DISPLAY => "Externer Bildschirm",
        self::USERROLE_PUBLIC => "Public"
    ];

    public function __construct(string $username, string $passwordHash)
    {
        $this->username = $username;
        $this->passwordHash = $passwordHash;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function setPasswordHash(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }

    
    public function getUserroleId(): int
    {
        return $this->userroleId ;
    }

    public function getUserroleBezeichnung(): string
    {
        return EA_User::USERROLE_LIST[$this->userroleId] ;
    }

    public function setUserroleId(int $userrole): void
    {
        $this->userroleId  = $userrole;
    }

    public function getUserroleBez(): string
    {
        return self::USERROLE_LIST[$this->userroleId ];
    }

    public function getUserroleList(): array
    {
        return self::USERROLE_LIST;
    }
}