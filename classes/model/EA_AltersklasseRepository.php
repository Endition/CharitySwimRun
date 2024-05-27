<?php
namespace CharitySwimRun\classes\model;

use DateTimeInterface;
use CharitySwimRun\classes\model\EA_Altersklasse;
use CharitySwimRun\classes\model\EA_Repository;
use Doctrine\ORM\EntityManager;


class EA_AltersklasseRepository extends EA_Repository
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
        return 0 === $this->entityManager->getRepository('CharitySwimRun\classes\model\EA_Altersklasse')->count([$field => $bezeichnung]);
    }
    
    public function create(EA_Altersklasse $altersklasse): EA_Altersklasse
    {
        $this->entityManager->persist($altersklasse);
        $this->update();
        return $altersklasse;
    }

    public function loadById(int $id): ?EA_Altersklasse
    {
        return $this->entityManager->getRepository('CharitySwimRun\classes\model\EA_Altersklasse')->find($id);
    }

    public function loadList(string $orderBy = "id"): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('a')
            ->from(EA_Altersklasse::class, 'a',"a.id")
            ->orderBy('a.'.$orderBy);        
        return $queryBuilder->getQuery()->getResult();
    }

    public function findByGeburtsjahr(DateTimeInterface $geburtsdatum): ?EA_Altersklasse
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('ak')
            ->from(EA_Altersklasse::class, 'ak')
            ->where("ak.uDatum <= :geburtsjahr AND ak.oDatum >= :geburtsjahr ")
            ->setMaxResults(1)
            ->setParameter(":geburtsjahr",$geburtsdatum->format("Y-m-d"));
        $query = $qb->getQuery();
        $result = $query->getOneOrNullResult();
        return $result;  
    }

    public function findByAlter(DateTimeInterface $gebDate, DateTimeInterface $referenzDate): ?EA_Altersklasse
    {
        $alter = $this->calcAlter($gebDate, $referenzDate);
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('ak')
            ->from(EA_Altersklasse::class, 'ak')
            ->where("`StartAlter` <= :alter  AND `EndeAlter` >= :alter")
            ->setMaxResults(1)
            ->setParameter(":alter",$alter);
        $query = $qb->getQuery();
        $result = $query->getSingleResult();
        return $result;  
    }

    public function loadListOrderBy(string $orderBy): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('ak')
            ->from(EA_Altersklasse::class, 'ak')
            ->where("1=1")
            ->orderBy('ak.'.$orderBy, 'ASC');
        $query = $qb->getQuery();
        $result = $query->getResult();
        return $result;
    }

    
    public function isInUse(EA_Altersklasse $altersklasse): bool
    {
        return ($altersklasse->getMitgliederList()->count() > 0);
    }

    public function delete(EA_Altersklasse $altersklasse): void
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