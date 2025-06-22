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
        // 1. Zuerst LIKE-Suche (Teilstring)
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('f')
            ->from(EA_FemaleFirstname::class, 'f')
            ->where($qb->expr()->like('f.firstname', ':name'))
            ->setParameter('name', '%' . $bezeichnung . '%')
            ->setMaxResults(1);
        $result = $qb->getQuery()->getOneOrNullResult();

        if ($result !== null) {
            return true;
        }

        // 2. Wenn kein LIKE-Treffer: unscharfe Suche mit Levenshtein
        $all = $this->entityManager->getRepository(EA_FemaleFirstname::class)->findAll();
        foreach ($all as $firstname) {
            if (levenshtein(mb_strtolower($bezeichnung), mb_strtolower($firstname->getFirstname())) <= $maxDistance) {
                return true;
            }
        }
        return false;
    }

}