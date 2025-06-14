<?php
namespace CharitySwimRun\classes\model;

use Doctrine\ORM\EntityManager;
use CharitySwimRun\classes\model\EA_Company;


class EA_CompanyRepository extends EA_Repository
{
    private EntityManager $entityManager;

        //own constructor is necassery, therefore all repositories use the same entitymanager
    public function __construct(EntityManager $entitymanager)
    {
        $this->entityManager = $entitymanager;
                //we need the same entityManager in the motherclass
                parent::setEntityManager($entitymanager); 
    }

    public function create(EA_Company $Unternehmen): EA_Company
    {
        $this->entityManager->persist($Unternehmen);
        $this->update();
        return $Unternehmen;
    }

    public function fusion(EA_Company $quellUnternehmen, EA_Company $zielUnternehmen): bool
    {
        $query = "UPDATE " . EA_Repository::TB_TEILNEHMER . " SET Unternehmen = :zielunternehmen WHERE Unternehmen = :ausgangsunternehmen ";
        $result = $this->entityManager->getConnection()->executeQuery($query,["ausgangsunternehmen"=>$quellUnternehmen->getId(),"zielunternehmen"=>$zielUnternehmen->getId()]);
        return true;
    }

    public function loadById(int $id): ?EA_Company
    {
        return $this->entityManager->getRepository('CharitySwimRun\classes\model\EA_Company')->find($id);
    }

    public function isAvailable(string $field,string $bezeichnung): int
    {
        return 0 === $this->entityManager->getRepository('CharitySwimRun\classes\model\EA_Company')->count([$field => $bezeichnung]);
    }

    public function isInUse(EA_Company $unternehmen): bool
    {
        return ($unternehmen->getMitgliederList()->count() > 0);
    }

    public function loadByBezeichnung(string $bezeichnung): ?EA_Company
    {
        return $this->entityManager->getRepository('CharitySwimRun\classes\model\EA_Company')->findOneBy(["unternehmen"=>$bezeichnung]);
    }

    public function loadList(string $orderBy = "id", ?string $searchUnternehmen = null): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('v')
            ->from(EA_Company::class, 'v',"v.id");
        if($searchUnternehmen){
            $queryBuilder->where("v.unternehmen LIKE :unternehmen")
            ->setParameter(":unternehmen", $searchUnternehmen."%");
        }
        $queryBuilder->orderBy('v.'.$orderBy);        
        return $queryBuilder->getQuery()->getResult();
    }

    public function delete(EA_Company $Unternehmen): void
    {
        $this->entityManager->remove($Unternehmen);
        $this->update();
    }

}