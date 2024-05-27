<?php
namespace EndeAuswertung\classes\model;

use Doctrine\ORM\EntityManager;
use EndeAuswertung\classes\model\EA_Urkundenelement;


class EA_UrkundenelementRepository extends EA_Repository
{
    private EntityManager $entityManager;

        //own constructor is necassery, therefore all repositories use the same entitymanager
    public function __construct(EntityManager $entitymanager)
    {
        $this->entityManager = $entitymanager;
                //we need the same entityManager in the motherclass
                parent::setEntityManager($entitymanager); 
    }

    public function create(EA_Urkundenelement $urkundenelement): EA_Urkundenelement
    {
        $this->entityManager->persist($urkundenelement);
        $this->update();
        return $urkundenelement;
    }

    public function loadById(int $id): ?EA_Urkundenelement
    {
        return $this->entityManager->getRepository('EndeAuswertung\classes\model\EA_Urkundenelement')->find($id);
    }

    public function loadList(): array
    {
        return $this->entityManager->getRepository("EndeAuswertung\classes\model\EA_Urkundenelement")->findAll();
    }

    public function loadListQueryBuilder(): array
    {
        //https://www.doctrine-project.org/projects/doctrine-orm/en/3.1/reference/query-builder.html
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('u')
            ->from(EA_Urkundenelement::class, 'u');
        return $qb->getQuery()->getResult();
    }

    public function delete(EA_Urkundenelement $urkundenelement): void
    {
        $this->entityManager->remove($urkundenelement);
        $this->update();
    }

}