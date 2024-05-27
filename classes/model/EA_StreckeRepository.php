<?php
namespace CharitySwimRun\classes\model;

use CharitySwimRun\classes\model\EA_Strecke;
use CharitySwimRun\classes\model\EA_Repository;
use Doctrine\ORM\EntityManager;

class EA_StreckeRepository extends EA_Repository
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
        return 0 === $this->entityManager->getRepository('CharitySwimRun\classes\model\EA_Strecke')->count([$field => $bezeichnung]);
    }
    
    public function create(EA_Strecke $strecke): EA_Strecke
    {
        $this->entityManager->persist($strecke);
        $this->update();
        return $strecke;
    }

    public function isInUse(EA_Strecke $strecke): bool
    {
        return ($strecke->getMitgliederList()->count() > 0);
    }

    public function loadById(int $id): ?EA_Strecke
    {
        return $this->entityManager->getRepository('CharitySwimRun\classes\model\EA_Strecke')->find($id);
    }

    public function loadByBezeichnungLang(string $bezeichnungLang): ?EA_Strecke
    {
        return $this->entityManager->getRepository('CharitySwimRun\classes\model\EA_Strecke')->findOneBy(["bezeichnungLang"=>$bezeichnungLang]);
    }

    public function loadList(string $orderBy = "id"): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('s')
            ->from(EA_Strecke::class, 's',"s.id")
            ->orderBy('s.'.$orderBy);        
        return $queryBuilder->getQuery()->getResult();
       // return $this->entityManager->getRepository("CharitySwimRun\classes\model\EA_Strecke")->findAll();
    }

    public function getListForSelectField(): array
    {
        $list = [];
        foreach($this->loadList("bezLang") as $strecke){
            $list[$strecke->getId()] = $strecke->getBezLang();
        }
        return $list;
    }

    public function delete(EA_Strecke $strecke): void
    {
        $this->entityManager->remove($strecke);
        $this->update();
    }

}