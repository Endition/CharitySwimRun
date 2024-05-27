<?php
namespace CharitySwimRun\classes\model;

use CharitySwimRun\classes\model\EA_Repository;
use Doctrine\ORM\EntityManager;



class EA_UserRepository extends EA_Repository
{
    private EntityManager $entityManager;

    //own constructor is necassery, therefore all repositories use the same entitymanager
    public function __construct(EntityManager $entitymanager)
    {
        $this->entityManager = $entitymanager;
                //we need the same entityManager in the motherclass
                parent::setEntityManager($entitymanager); 
    }

    public function create(EA_User $user): EA_User
    {
        $this->entityManager->persist($user);
        $this->update();
        return $user;
    }

    
    public function loadByUsername(string $username): ?EA_User
    {
        return $this->entityManager->getRepository('CharitySwimRun\classes\model\EA_User')->findOneBy(array('username' => $username));
    }

    public function checkIfAdminExist(): ?EA_User
    {
        return $this->entityManager->getRepository('CharitySwimRun\classes\model\EA_User')->findOneBy(array('userroleId' => EA_User::USERROLE_ADMIN));
    }

    public function loadById(int $id): ?EA_User
    {
        return $this->entityManager->getRepository('CharitySwimRun\classes\model\EA_User')->find($id);
    }

    public function loadList(): array
    {
        return $this->entityManager->getRepository("CharitySwimRun\classes\model\EA_User")->findAll();
    }

    public function delete(EA_User $user): void
    {
        $this->entityManager->remove($user);
        $this->update();
    }

    public function checkCredentials(string $passwortEingabe,string $passwordHashStored): bool
    {
        return password_verify($passwortEingabe, $passwordHashStored);
    }

}