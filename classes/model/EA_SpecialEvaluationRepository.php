<?php
namespace EndeAuswertung\classes\model;

use EndeAuswertung\classes\model\EA_SpecialEvaluation;
use EndeAuswertung\classes\model\EA_Repository;
use Doctrine\ORM\EntityManager;


class EA_SpecialEvaluationRepository extends EA_Repository
{
    
    private EntityManager $entityManager;

    //own constructor is necassery, therefore all repositories use the same entitymanager
    public function __construct(EntityManager $entitymanager)
    {
        $this->entityManager = $entitymanager;
                //we need the same entityManager in the motherclass
                parent::setEntityManager($entitymanager); 
    }

    
    public function create(EA_SpecialEvaluation $altersklasse): EA_SpecialEvaluation
    {
        $this->entityManager->persist($altersklasse);
        $this->update();
        return $altersklasse;
    }

    public function loadById(int $id): ?EA_SpecialEvaluation
    {
        return $this->entityManager->getRepository('EndeAuswertung\classes\model\EA_SpecialEvaluation')->find($id);
    }

    public function loadList(string $orderBy = "id"): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('s')
            ->from(EA_SpecialEvaluation::class, 's',"s.id")
            ->orderBy('s.'.$orderBy);        
        return $queryBuilder->getQuery()->getResult();
    }

    public function delete(EA_SpecialEvaluation $altersklasse): void
    {
        $this->entityManager->remove($altersklasse);
        $this->update();
    }

    public function isAvailable(string $field,string $bezeichnung): int
    {
        return 0 === $this->entityManager->getRepository('EndeAuswertung\classes\model\EA_SpecialEvaluation')->count([$field => $bezeichnung]);
    }

    public function getListForSelectField(): array
    {
        $list = [];
        foreach($this->loadList("name") as $specialEvaluation){
            $list[$specialEvaluation->getId()] = $specialEvaluation->getName();
        }
        return $list;
    }
}