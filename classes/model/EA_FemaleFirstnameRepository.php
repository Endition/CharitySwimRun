<?php
namespace CharitySwimRun\classes\model;

use Doctrine\ORM\EntityManager;
use CharitySwimRun\classes\model\EA_FemaleFirstname;


class EA_FemaleFirstnameRepository extends EA_Repository
{
    private EntityManager $entityManager;

        //own constructor is necassery, therefore all repositories use the same entitymanager
    public function __construct(EntityManager $entitymanager)
    {
        $this->entityManager = $entitymanager;
                //we need the same entityManager in the motherclass
                parent::setEntityManager($entitymanager); 
    }


    public function isFemaleFirstname(string $bezeichnung, int $maxDistance = 2): bool
    {
        /**
         * We do an exact case-insensitive match here.
         * Using LIKE '%name%' would match any female name containing the input (e.g. 'Al' -> 'Alice').
         * Using Levenshtein distance causes male names to be incorrectly matched 
         * as female (e.g. 'Paul' -> 'Paula' [dist 1], 'Tim' -> 'Kim' [dist 1], 'Mario' -> 'Maria' [dist 1]).
         */
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('f')
            ->from(EA_FemaleFirstname::class, 'f')
            ->where('f.firstname = :name')
            ->setParameter('name', trim($bezeichnung))
            ->setMaxResults(1);
        
        $result = $qb->getQuery()->getOneOrNullResult();

        return $result !== null;
    }

}