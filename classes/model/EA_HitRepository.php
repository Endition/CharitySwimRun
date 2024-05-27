<?php
namespace CharitySwimRun\classes\model;

use CharitySwimRun\classes\model\EA_Hit;
use CharitySwimRun\classes\model\EA_Repository;
use Doctrine\ORM\EntityManager;

class EA_HitRepository extends EA_Repository
{
    
    private EntityManager $entityManager;

    //own constructor is necassery, therefore all repositories use the same entitymanager
    public function __construct(EntityManager $entitymanager)
    {
        $this->entityManager = $entitymanager;
                //we need the same entityManager in the motherclass
                parent::setEntityManager($entitymanager); 
    }
    
    public function create(EA_Hit $impuls): EA_Hit
    {
        $this->entityManager->persist($impuls);
        $this->update();
        return $impuls;
    }

    public function loadById(int $id): ?EA_Hit
    {
        return $this->entityManager->getRepository('CharitySwimRun\classes\model\EA_Hit')->find($id);
    }

    public function loadList(?string $order = "i.timestamp", ?string $orderRichtung = "ASC", ?int $limit = null,?string $groupby = null, ?int $bigerAsTimestamp=null): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('i')
            ->from(EA_Hit::class, 'i')
            ->where("i.geloescht = 0");
        if($bigerAsTimestamp){
            $queryBuilder->andWhere("i.timestamp > :biggerAsTimestamp")
            ->setParameter(":biggerAsTimestamp",$bigerAsTimestamp);
        }

        $queryBuilder->orderBy($order,$orderRichtung);
        if($groupby){
            $queryBuilder->groupBy($groupby);
         } 
        if($limit){
            $queryBuilder->setMaxResults($limit);
        }  
        $query = $queryBuilder->getQuery();
        #$query->enableResultCache();
        return $query->getResult();
    }

    public function getNewestEntry(): ?EA_Hit
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('i')
            ->from(EA_Hit::class, 'i')
            ->where("i.geloescht = 0")
            ->orderBy("i.id","DESC")
            ->setMaxResults(1);
    
        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
    
    public function getNumberOfEntries(): int
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('count(i.id) as numberOfEntries')
            ->from(EA_Hit::class, 'i')
            ->where("i.geloescht = 0");
        $result = $queryBuilder->getQuery()->getSingleScalarResult();
        return intval($result ?? 0);
    }

    public function updateImpulseCache(): void
    {
        $query = "UPDATE `".EA_Repository::TB_TEILNEHMER."` t
        INNER JOIN ".EA_Repository::TB_LOG." i1 ON i1.TeilnehmerId = t.Id
        SET t.impulseCache = (SELECT COUNT(i.ImpulsId) FROM ".EA_Repository::TB_LOG." i WHERE i.geloescht = 0 AND i.TeilnehmerId = t.Id GROUP BY i.TeilnehmerId); ";
        $this->entityManager->getConnection()->executeQuery($query);
    }

    public function loadFehlbuchungen(): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('i')
            ->from(EA_Hit::class, 'i')
            ->where("i.teilnehmer IS NULL");
          
        return $queryBuilder->getQuery()->getResult();
    }

    public function delete(EA_Hit $impuls): void
    {
        $this->entityManager->remove($impuls);
        $this->update();
    }

    public function deleteAllByTeilnehmer(EA_Starter $EA_Starter): bool
    {
        $query = $this->entityManager->createQuery('DELETE CharitySwimRun\classes\model\EA_Hit i WHERE i.teilnehmerId = :teilnehmerId');
        $query->setParameter(":teilnehmerId",$EA_Starter->getId());
        $query->execute();
        return true;
    }

    public function getGlobaleVeranstaltungsleistungsdaten(int $gestarteteTeilnehmer, EA_Configuration $konfiguration): array
    {
        $data = [];
        $data['streckenart'] = $konfiguration->getStreckenart();
        $data['gestarteteTeilnehmer'] = $gestarteteTeilnehmer;
        $data['erreichteImpulse'] = $this->getNumberOfEntries();

        $data['spendensumme'] = $konfiguration->getGeld();
        $data['erreichteMeter'] = $data['erreichteImpulse'] * $konfiguration->getRundenlaenge();
        $data['erreichtesGeld'] = $data['erreichteMeter'] * $konfiguration->getEuroprometer();
        $data['anzahlStreckenart'] = $data['erreichteImpulse'] * $konfiguration->getFaktor();
        $data['erreichteMeterProTeilnehmer'] = ($data['gestarteteTeilnehmer'] > 0) ? round(($data['erreichteMeter'] / $data['gestarteteTeilnehmer']),2) : 0;
        // Div by 0 wenn keine Einstellungen vorhanden sind
        if ($konfiguration->getEuroprometer() > 0) {
                $data['zielmeter'] = $data['spendensumme'] / $konfiguration->getEuroprometer();
        } else {
                $data['zielmeter'] = 0;
        }
        $data['restmeter'] = $data['zielmeter'] - $data['erreichteMeter'];
        if (($data['spendensumme'] - $data['erreichtesGeld'] ) > 0) {
            $data['restgeld'] = $data['spendensumme'] - $data['erreichtesGeld'];
            $data['erreichteProzent'] = ($data['erreichtesGeld'] > 0) ? round($data['erreichtesGeld'] / $data['spendensumme'] * 100, 2) : 0;
        } else {
            //Fall wenn Spendensumme erschwommen
            $data['erreichtesGeld'] = $data['spendensumme'];
            $data['restmeter'] = 0;
            $data['restgeld'] = 0;
            $data['erreichteProzent'] = 100;
         }

        return $data;
    }

}