<?php
namespace CharitySwimRun\classes\model;

use DateTimeInterface;
use CharitySwimRun\classes\model\EA_AgeGroup;
use CharitySwimRun\classes\model\EA_Repository;
use Doctrine\ORM\EntityManager;


class EA_AgeGroupRepository extends EA_Repository
{
    
    private EntityManager $entityManager;

    //own constructor is necassery, therefore all repositories use the same entitymanager
    public function __construct(EntityManager $entitymanager)
    {
        $this->entityManager = $entitymanager;
                //we need the same entityManager in the motherclass
                parent::setEntityManager($entitymanager); 
    }

    public function isAvailable(string $field,string $bezeichnung): int
    {
        return 0 === $this->entityManager->getRepository('CharitySwimRun\classes\model\EA_AgeGroup')->count([$field => $bezeichnung]);
    }
    
    public function create(EA_AgeGroup $altersklasse): EA_AgeGroup
    {
        $this->entityManager->persist($altersklasse);
        $this->update();
        return $altersklasse;
    }

    public function loadById(int $id): ?EA_AgeGroup
    {
        return $this->entityManager->getRepository('CharitySwimRun\classes\model\EA_AgeGroup')->find($id);
    }

    public function loadList(string $orderBy = "id"): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('a')
            ->from(EA_AgeGroup::class, 'a',"a.id")
            ->orderBy('a.'.$orderBy);        
        return $queryBuilder->getQuery()->getResult();
    }

    public function findByGeburtsjahr(DateTimeInterface $geburtsdatum): ?EA_AgeGroup
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('ak')
            ->from(EA_AgeGroup::class, 'ak')
            ->where("ak.uDatum <= :geburtsjahr AND ak.oDatum >= :geburtsjahr ")
            ->setMaxResults(1)
            ->setParameter(":geburtsjahr",$geburtsdatum->format("Y-m-d"));
        $query = $qb->getQuery();
        $result = $query->getOneOrNullResult();
        return $result;  
    }

    public function findByAlter(DateTimeInterface $gebDate, DateTimeInterface $referenzDate): ?EA_AgeGroup
    {
        $alter = $this->calcAlter($gebDate, $referenzDate);
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('ak')
            ->from(EA_AgeGroup::class, 'ak')
            ->where("ak.startAlter <= :alter1  AND ak.endeAlter >= :alter2")
            ->setMaxResults(1)
            ->setParameter(":alter1",$alter)
            ->setParameter(":alter2",$alter);
        $query = $qb->getQuery();
        $result = $query->getSingleResult();
        return $result;  
    }

    public function loadListOrderBy(string $orderBy): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('ak')
            ->from(EA_AgeGroup::class, 'ak')
            ->where("1=1")
            ->orderBy('ak.'.$orderBy, 'ASC');
        $query = $qb->getQuery();
        $result = $query->getResult();
        return $result;
    }

    
    public function isInUse(EA_AgeGroup $altersklasse): bool
    {
        return ($altersklasse->getMitgliederList()->count() > 0);
    }

    public function delete(EA_AgeGroup $altersklasse): void
    {
        $this->entityManager->remove($altersklasse);
        $this->update();
    }

    private function calcAlter(DateTimeInterface $gebDate, DateTimeInterface $referenzDate): int
    {
            $differenz = $gebDate->diff($referenzDate);
            return (int)$differenz->format('%y');
    }

}