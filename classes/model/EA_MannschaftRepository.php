<?php
namespace EndeAuswertung\classes\model;


use EndeAuswertung\classes\model\EA_Mannschaft;
use EndeAuswertung\classes\model\EA_Repository;
use Doctrine\ORM\EntityManager;

class EA_MannschaftRepository extends EA_Repository
{
    
    private EntityManager $entityManager;

    //own constructor is necassery, therefore all repositories use the same entitymanager
    public function __construct(EntityManager $entitymanager)
    {
        $this->entityManager = $entitymanager;
        //we need the same entityManager in the motherclass
        parent::setEntityManager($entitymanager); 
    }
    public function create(EA_Mannschaft $mannschaft): EA_Mannschaft
    {
        $this->entityManager->persist($mannschaft);
        $this->update();
        return $mannschaft;
    }

    public function isAvailable(string $field,string $bezeichnung): int
    {
        return 0 === $this->entityManager->getRepository('EndeAuswertung\classes\model\EA_Mannschaft')->count([$field => $bezeichnung]);
    }

    public function loadById(int $id): ?EA_Mannschaft
    {
        return $this->entityManager->getRepository('EndeAuswertung\classes\model\EA_Mannschaft')->find($id);
    }

    public function loadList(string $orderBy = "id"): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select("m")
            ->from(EA_Mannschaft::class, 'm',"m.id")
            ->orderBy('m.'.$orderBy);        
        return $queryBuilder->getQuery()->getResult();
        //return $this->entityManager->getRepository("EndeAuswertung\classes\model\EA_Mannschaft")->findAll();
    }

    public function getListForSelectField(): array
    {
        $list = [];
        foreach($this->loadList("mannschaft") as $mannschaft){
            $list[$mannschaft->getId()] = $mannschaft->getMannschaft();
        }
        return $list;
    }

    public function delete(EA_Mannschaft $user): void
    {
        $this->entityManager->remove($user);
        $this->update();
    }

    public function MannschaftPunkteBerechnen(?EA_Mannschaft $mannschaft2, EA_Konfiguration $konfiguration): void
    {
        $punkte = 0;
        $mannschaftList = [];
        if ($mannschaft2) {
            $mannschaftList[] = $this->loadById($mannschaft2->getId());
        } else {
            $mannschaftList = $this->loadList();
        }
        if (!is_array($mannschaftList) || count($mannschaftList) === 0) {
            return;
        }
        foreach ($mannschaftList as $mannschaft) {
            if ($konfiguration->getMannschaftPunkteBerechnen() === "Gesamtstrecke") {
                $punkte = $mannschaft->getGesamtMeter();
            } else {
                foreach ($mannschaft->getMitgliederList() as $mitglied) {
                    if ($mitglied->getMeter() > 0) {
                        if ($mitglied->getAltersklasse()->getWertungsschluessel() > 0) { // Division durch 0 verhindern
                            $punkte = $punkte + ($mitglied->getMeter() / $mitglied->getAltersklasse()->getWertungsschluessel());
                        } else {
                            $punkte = $punkte + ($mitglied->getMeter() / 10);
                        }
                    }
                }
                $punkte = $punkte / $mannschaft->getMitgliederList()->count(); // Gesamtsumme durch Mitglieder
            }

            if ($punkte > 0) {
                $mannschaft->setPunkte($punkte);
                $punkte = 0; // Speichern
            }
        }
        $this->update();
    }

}