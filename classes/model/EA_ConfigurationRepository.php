<?php
namespace CharitySwimRun\classes\model;

use CharitySwimRun\classes\model\EA_Configuration;
use CharitySwimRun\classes\model\EA_Repository;
use Doctrine\ORM\EntityManager;

class EA_ConfigurationRepository extends EA_Repository
{
    
    private EntityManager $entityManager;

    //own constructor is necassery, therefore all repositories use the same entitymanager
    public function __construct(EntityManager $entitymanager)
    {
        $this->entityManager = $entitymanager;
                //we need the same entityManager in the motherclass
                parent::setEntityManager($entitymanager); 
    }
    
    public function create(EA_Configuration $konfiguration): EA_Configuration
    {
        $this->entityManager->persist($konfiguration);
        $this->update();
        return $konfiguration;
    }


    public function load(): ?EA_Configuration
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('k')
            ->from(EA_Configuration::class, 'k');
        return $queryBuilder->getQuery()->getOneOrNullResult();  
    }
}