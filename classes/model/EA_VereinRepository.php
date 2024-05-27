<?php
namespace CharitySwimRun\classes\model;

use Doctrine\ORM\EntityManager;
use CharitySwimRun\classes\model\EA_Verein;


class EA_VereinRepository extends EA_Repository
{
    private EntityManager $entityManager;

        //own constructor is necassery, therefore all repositories use the same entitymanager
    public function __construct(EntityManager $entitymanager)
    {
        $this->entityManager = $entitymanager;
                //we need the same entityManager in the motherclass
                parent::setEntityManager($entitymanager); 
    }

    public function create(EA_Verein $Verein): EA_Verein
    {
        $this->entityManager->persist($Verein);
        $this->update();
        return $Verein;
    }

    public function fusion(EA_Verein $quellVerein, EA_Verein $zielVerein): bool
    {
        $query = "UPDATE " . EA_Repository::TB_TEILNEHMER . " SET Verein = :zielverein WHERE Verein = :ausgangsverein ";
        $result = $this->entityManager->getConnection()->executeQuery($query,["ausgangsverein"=>$quellVerein->getId(),"zielverein"=>$zielVerein->getId()]);
        return true;
    }

    public function loadById(int $id): ?EA_Verein
    {
        return $this->entityManager->getRepository('CharitySwimRun\classes\model\EA_Verein')->find($id);
    }

    public function isAvailable(string $field,string $bezeichnung): int
    {
        return 0 === $this->entityManager->getRepository('CharitySwimRun\classes\model\EA_Verein')->count([$field => $bezeichnung]);
    }

    public function isInUse(EA_Verein $verein): bool
    {
        return ($verein->getMitgliederList()->count() > 0);
    }

    public function loadByBezeichnung(string $bezeichnung): ?EA_Verein
    {
        return $this->entityManager->getRepository('CharitySwimRun\classes\model\EA_Verein')->findOneBy(["verein"=>$bezeichnung]);
    }

    public function loadList(string $orderBy = "id", ?string $searchVerein = null): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('v')
            ->from(EA_Verein::class, 'v',"v.id");
        if($searchVerein){
            $queryBuilder->where("v.verein LIKE :verein")
            ->setParameter(":verein", $searchVerein."%");
        }
        $queryBuilder->orderBy('v.'.$orderBy);        
        return $queryBuilder->getQuery()->getResult();
    }

    public function delete(EA_Verein $Verein): void
    {
        $this->entityManager->remove($Verein);
        $this->update();
    }

}