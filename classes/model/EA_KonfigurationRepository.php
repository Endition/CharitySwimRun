<?php
namespace EndeAuswertung\classes\model;

use EndeAuswertung\classes\model\EA_Konfiguration;
use EndeAuswertung\classes\model\EA_Repository;
use Doctrine\ORM\EntityManager;

class EA_KonfigurationRepository extends EA_Repository
{
    
    private EntityManager $entityManager;

    //own constructor is necassery, therefore all repositories use the same entitymanager
    public function __construct(EntityManager $entitymanager)
    {
        $this->entityManager = $entitymanager;
                //we need the same entityManager in the motherclass
                parent::setEntityManager($entitymanager); 
    }
    
    public function create(EA_Konfiguration $konfiguration): EA_Konfiguration
    {
        $this->entityManager->persist($konfiguration);
        $this->update();
        return $konfiguration;
    }


    public function load(): ?EA_Konfiguration
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('k')
            ->from(EA_Konfiguration::class, 'k');
        return $queryBuilder->getQuery()->getOneOrNullResult();  
    }
}