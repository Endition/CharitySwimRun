<?php
namespace CharitySwimRun\classes\model;

use CharitySwimRun\classes\model\EA_TeamCategory;
use CharitySwimRun\classes\model\EA_Repository;
use Doctrine\ORM\EntityManager;

class EA_TeamCategoryRepository extends EA_Repository
{
    
    private EntityManager $entityManager;

    //own constructor is necassery, therefore all repositories use the same entitymanager
    public function __construct(EntityManager $entitymanager)
    {
        $this->entityManager = $entitymanager;
                //we need the same entityManager in the motherclass
                parent::setEntityManager($entitymanager); 
    }
    
    public function create(EA_TeamCategory $user): EA_TeamCategory
    {
        $this->entityManager->persist($user);
        $this->update();
        return $user;
    }

    public function loadByBezeichnung(string $bezeichnung): ?EA_TeamCategory
    {
        return $this->entityManager->getRepository('CharitySwimRun\classes\model\EA_TeamCategory')->findOneBy(array('mannschaftskategorie' => $bezeichnung));
    }

    public function loadById(int $id): ?EA_TeamCategory
    {
        return $this->entityManager->getRepository('CharitySwimRun\classes\model\EA_TeamCategory')->find($id);
    }

    public function loadList(): array
    {
        return $this->entityManager->getRepository("CharitySwimRun\classes\model\EA_TeamCategory")->findAll();
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

    public function delete(EA_TeamCategory $user): void
    {
        $this->entityManager->remove($user);
        $this->update();
    }

}