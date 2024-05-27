<?php
namespace CharitySwimRun\classes\model;

use CharitySwimRun\classes\model\EA_Mannschaftskategorie;
use CharitySwimRun\classes\model\EA_Repository;
use Doctrine\ORM\EntityManager;

class EA_MannschaftskategorieRepository extends EA_Repository
{
    
    private EntityManager $entityManager;

    //own constructor is necassery, therefore all repositories use the same entitymanager
    public function __construct(EntityManager $entitymanager)
    {
        $this->entityManager = $entitymanager;
                //we need the same entityManager in the motherclass
                parent::setEntityManager($entitymanager); 
    }
    
    public function create(EA_Mannschaftskategorie $user): EA_Mannschaftskategorie
    {
        $this->entityManager->persist($user);
        $this->update();
        return $user;
    }

    public function loadByBezeichnung(string $bezeichnung): ?EA_Mannschaftskategorie
    {
        return $this->entityManager->getRepository('CharitySwimRun\classes\model\EA_Mannschaftskategorie')->findOneBy(array('mannschaftskategorie' => $bezeichnung));
    }

    public function loadById(int $id): ?EA_Mannschaftskategorie
    {
        return $this->entityManager->getRepository('CharitySwimRun\classes\model\EA_Mannschaftskategorie')->find($id);
    }

    public function loadList(): array
    {
        return $this->entityManager->getRepository("CharitySwimRun\classes\model\EA_Mannschaftskategorie")->findAll();
    }

    public function loadListForSelect(): array
    {
        $list = $this->loadList();
        $selectArray = [];
        foreach($list as $element){
            $selectArray[$element->getId()] = $element->getMannschaftskategorie();
        }
        return $selectArray;
    }

    public function delete(EA_Mannschaftskategorie $user): void
    {
        $this->entityManager->remove($user);
        $this->update();
    }

}