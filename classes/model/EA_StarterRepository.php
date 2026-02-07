<?php
namespace CharitySwimRun\classes\model;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\EntityManager;
use CharitySwimRun\classes\model\EA_Starter;
use CharitySwimRun\classes\helper\EA_Helper;
use Doctrine\ORM\Query\ResultSetMapping;



class EA_StarterRepository extends EA_Repository
{
    private EntityManager $entityManager;

        //own constructor is necassery, therefore all repositories use the same entitymanager
    public function __construct(EntityManager $entitymanager)
    {
        $this->entityManager = $entitymanager;
                //we need the same entityManager in the motherclass
                parent::setEntityManager($entitymanager);   
    }

    public function create(EA_Starter $Teilnehmer): EA_Starter
    {
        $this->entityManager->persist($Teilnehmer);
        $this->update();
        return $Teilnehmer;
    }

    public function loadById(int $id): ?EA_Starter
    {
        return $this->entityManager->getRepository('CharitySwimRun\classes\model\EA_Starter')->find($id);
    }

    public function loadByStartnummer(int $startnummer): ?EA_Starter
    {
        return $this->entityManager->getRepository('CharitySwimRun\classes\model\EA_Starter')->findOneBy(["startnummer"=>$startnummer]);
    }

    public function loadByTransponder(int $transponder): ?EA_Starter
    {
        return $this->entityManager->getRepository('CharitySwimRun\classes\model\EA_Starter')->findOneBy(["transponder"=>$transponder]);
    }

    public function loadByFilter(
        ?int $notThisId = null, 
        ?int $startnummer = null, 
        ?int $transponder = null, 
        ?string $vorname = null, 
        ?string $name = null, 
        ?DateTimeInterface $geburtsdatum = null, 
        ?string $geschlecht = null, 
        ?bool $hasStartzeit = null,
        ?int $gesamtplatz = null): ?EA_Starter
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('t')
            ->from(EA_Starter::class, 't',"t.id");
        if($notThisId){
            $queryBuilder->andWhere("t.id != :notThisId")
            ->setParameter(":notThisId", $notThisId);
        } 
        if($startnummer){
            $queryBuilder->andWhere("t.startnummer = :startnummer")
            ->setParameter(":startnummer", $startnummer);
        } 
        if($transponder){
            $queryBuilder->andWhere("t.transponder = :transponder")
            ->setParameter(":transponder", $transponder);
        } 
        if($vorname){
            $queryBuilder->andWhere("t.vorname = :vorname")
            ->setParameter(":vorname", $vorname);
        }  
        if($name){
            $queryBuilder->andWhere("t.name = :name")
            ->setParameter(":name", $name);
        }  
        if($geburtsdatum){
            $queryBuilder->andWhere("t.geburtsdatum = :geburtsdatum")
            ->setParameter(":geburtsdatum", $geburtsdatum->format("Y-m-d"));
        }  
        if($geschlecht){
            $queryBuilder->andWhere("t.geschlecht = :geschlecht")
            ->setParameter(":geschlecht", $geschlecht);
        }   
        if($gesamtplatz){
            $queryBuilder->andWhere("t.gesamtplatz = :gesamtplatz")
            ->setParameter(":gesamtplatz", $gesamtplatz);
        }  
        if($hasStartzeit === true){
            $queryBuilder->andWhere("t.startzeit IS NOT NULL AND t.startzeit != '0000-00-00 00:00:00' ");
        }   
        $queryBuilder->setMaxResults(1); 
        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function loadOldestYoungstByGender(string $gender, string $sort = "ASC"): ?EA_Starter
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('t')
            ->from(EA_Starter::class, 't')
            ->where("t.geschlecht = :geschlecht")
            ->andWhere("t.startzeit IS NOT NULL AND t.startzeit != '0000-00-00 00:00:00' ")
            ->orderBy("t.geburtsdatum", $sort)
            ->setMaxResults(1)
            ->setParameter(":geschlecht", $gender);
          
        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function getAnzahlTeilnehmer(): int
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('count(t.id)')
            ->from(EA_Starter::class, 't');
          
        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    public function loadList(
        ?EA_Distance $strecke = null, 
        ?EA_AgeGroup $altersklasse = null, 
        ?string $geschlecht = null, 
        ?EA_Team $mannschaft = null, 
        ?EA_Club $verein = null,
        ?int $startgruppe = null,
        ?bool $transponderIsNull = null, 
        string $orderBy = "id", 
        string $orderDirection = "ASC",
        ?bool $hasStartzeit = null,
        ?bool $hasBuchung = null,
        ?string $wertung = null,
        ?int $status = null,
        ?int $teilnehmerId = null,
        ?int $searchStartnummer = null,
        ?string $searchName = null,
        ?string $searchVorname = null,
        ?int $limit = null,
        ?EA_SpecialEvaluation $specialEvaluation = null,
        ?int $moreHitsThan = null
        ): ?array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('t')
            ->from(EA_Starter::class, 't',"t.id")
            ->leftJoin('t.verein', 'verein')
            ->leftJoin('t.unternehmen', 'unternehmen')
            ->leftJoin('t.strecke', 'strecke')
            ->leftJoin('t.altersklasse', 'altersklasse');
        if($specialEvaluation){
             $queryBuilder->leftjoin(EA_Hit::class,'i','WITH', 'i.teilnehmer = t.id');
        }    
          
        if($strecke){
            $queryBuilder->andWhere("t.strecke = :strecke");
            $queryBuilder->setParameter(":strecke",$strecke->getId());
        }
        if($altersklasse){
            $queryBuilder->andWhere("t.altersklasse = :altersklasse");
            $queryBuilder->setParameter(":altersklasse",$altersklasse->getId());
        }
        if($verein){
            $queryBuilder->andWhere("t.verein = :verein");
            $queryBuilder->setParameter(":verein",$verein->getId());
        }
        if($mannschaft){
            $queryBuilder->andWhere("t.mannschaft = :mannschaft");
            $queryBuilder->setParameter(":mannschaft",$mannschaft->getId());
        }
        if($geschlecht){
            $queryBuilder->andWhere("t.geschlecht = :geschlecht");
            $queryBuilder->setParameter(":geschlecht",$geschlecht);
        }
        if($startgruppe){
            $queryBuilder->andWhere("t.startgruppe = :startgruppe");
            $queryBuilder->setParameter(":startgruppe",$startgruppe);
        }
        if($status){
            $queryBuilder->andWhere("t.status = :status");
            $queryBuilder->setParameter(":status",$status);
        }
        if($transponderIsNull === true){
            $queryBuilder->andWhere("t.transponder IS NULL");
        }
        if($transponderIsNull === false){
            $queryBuilder->andWhere("t.transponder IS NOT NULL");
        }
        if($hasStartzeit === true){
            $queryBuilder->andWhere("t.startzeit IS NOT NULL");
            $queryBuilder->andWhere("t.startzeit != '0000-00-00 00:00:00'");
        }
        if($hasStartzeit === false){
            $queryBuilder->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->eq("t.startzeit", ':startzeit'),
                    $queryBuilder->expr()->isNull("t.startzeit")
                )
            );
            $queryBuilder->setParameter(":startzeit",'0000-00-00 00:00:00');
        }
        if($moreHitsThan){
            $queryBuilder->andWhere("t.impulseCache > :moreHitsThan");
            $queryBuilder->setParameter(":moreHitsThan",$moreHitsThan);
        }

        if($teilnehmerId){
            $queryBuilder->andWhere("t.id = :id");
            $queryBuilder->setParameter(":id",$teilnehmerId);
        }
        if($searchStartnummer){
            $queryBuilder->andWhere("t.startnummer LIKE :searchStartnummer");
            $queryBuilder->setParameter(":searchStartnummer",$searchStartnummer. '%');
        }
        if($searchName || $searchVorname){
            $orX = $queryBuilder->expr()->orX();
            if ($searchName) {
                $orX->add($queryBuilder->expr()->like('t.name', ':searchName'));
                $queryBuilder->setParameter('searchName', $searchName . '%');
            }
            if ($searchVorname) {
                $orX->add($queryBuilder->expr()->like('t.vorname', ':searchVorname'));
                $queryBuilder->setParameter('searchVorname', $searchVorname . '%');
            }
            $queryBuilder->andWhere($orX);
        }
        if($specialEvaluation){
            $queryBuilder->andWhere("i.timestamp > :start")
            ->andWhere("i.timestamp < :end")
            ->setParameter(":start",$specialEvaluation->getStart()->getTimestamp())
            ->setParameter(":end",$specialEvaluation->getEnd()->getTimestamp())
            ->groupBy('t.id');
        }  
        if($orderBy !== ""){
            $queryBuilder->orderBy('t.'.$orderBy, $orderDirection);        
        }else{
            $queryBuilder->orderBy('t.id',"ASC");        
        }

        if($limit){
            $queryBuilder->setMaxResults($limit);
        }
        $query = $queryBuilder->getQuery();
        $query->enableResultCache();

        return $query->getResult();
    }

    public function loadListSmartyZugriff(?int $streckeId, ?int $altersklasseId, ?string $geschlecht, ?string $platzfilter = null, string $order = "gesamtplatz", ?int $moreHitsThan = null): array
    {
        $strecke = null;
        $altersklasse = null;
        $limit = null;
        if($streckeId){
            $strecke = new EA_Distance();
            $strecke->setId($streckeId);
        }
        if($altersklasseId){
            $altersklasse = new EA_AgeGroup();
            $altersklasse->setId($altersklasseId);
        }
        if($platzfilter){
            $limit = 3;
        }
        return $this->loadList($strecke,$altersklasse,$geschlecht,null,null,null,null,$order,"ASC",null,null,null,null,null,null,null,null,$limit,null,$moreHitsThan);
    }

    public function delete(EA_Starter $Teilnehmer): void
    {
        $this->entityManager->remove($Teilnehmer);
        $this->update();
    }

    public function loadMedaillenspiegel(array $teilnehmerList): array
    {
        $spiegel = [];
            foreach ($teilnehmerList as $tn) {
                //nur wenn auch Werte hinterlegt sind, Spiegel berechnen
                if ($tn->getAltersklasse()->getUrkunde() > 0 && $tn->getAltersklasse()->getBronze() > 0) {
                    //Wenn das Array noch nicht existiert, anlegen
                    if (!isset($spiegel[$tn->getAltersklasse()->getId()][$tn->getWertung("kurz")])) {
                        $spiegel[$tn->getAltersklasse()->getId()][$tn->getWertung("kurz")] = 0;
                    }
                    $spiegel[$tn->getAltersklasse()->getId()][$tn->getWertung("kurz")] = $spiegel[$tn->getAltersklasse()->getId()][$tn->getWertung("kurz")] + 1;
                    $spiegel[$tn->getAltersklasse()->getId()]['AK_Name'] = $tn->getAltersklasse()->getAltersklasse();
                } else {
                    $spiegel[$tn->getAltersklasse()->getId()][$tn->getWertung("kurz")] = 0;
                    $spiegel[$tn->getAltersklasse()->getId()]['AK_Name'] = $tn->getAltersklasse()->getAltersklasse();
                }
            }
        return $spiegel;
    }

    public function loadInformationZielmarkeErreicht(): array
    {
        $teilnehmerList = $this->loadList(null,null,null,null,null,null,null,"","",true, true);
        $nachrichten = [];
        $i = 0;
        if (is_array($teilnehmerList) && count($teilnehmerList) > 0 && $teilnehmerList != "") {
            foreach ($teilnehmerList as $tn) {
                $checkvalue = [];
                $checkvalue['gold'] = ($tn->getAltersklasse()->getGold() > 0) ? $tn->getAltersklasse()->getGold() : null;
                foreach ($checkvalue as $value) {
                    if ($value > 0) {
                        $untereGrenze = $value - 300;
                        $obereGrenze = $value + 300;
                        if ($tn->getMeter() >= $untereGrenze && $tn->getMeter() <= $obereGrenze) {
                            $nachrichten[$i]['text'] = $tn->getMeter() . "m erreicht.";
                            $nachrichten[$i]['tn'] = $tn;
                            $i++;
                        }
                    }
                }
            }
        }
        return $nachrichten;
    }

    public function resetPlaetzeTeilnehmer(?EA_Starter $teilnehmer = null): void
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->update(EA_Starter::class, 't')
        ->set('t.gesamtplatz', 99999)
        ->set('t.streckenplatz', 99999)
        ->set('t.akplatz', 99999);
        if($teilnehmer){
            $queryBuilder->where('t.id = :editId')
            ->setParameter('editId', $teilnehmer->getId());
        }
        $queryBuilder->getQuery()->execute();
    }

    public function updateStartzeit(DateTimeInterface $startzeit, bool $ueberschreiben = false, ?string $geschlecht = null ,?string $spalteName = null, ?string $spalteWertString = null, ?array $spalteWertArray = null): int
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->update(EA_Starter::class, 't')
        ->set('t.startzeit', ":startzeit")
        ->set('t.status', EA_Starter::STATUS_GESTARTET)
        ->setParameter("startzeit",$startzeit->format("Y-m-d H:i:s"));
        if($ueberschreiben === false){
            $queryBuilder->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->eq("t.startzeit", ':startzeitAlt'),
                    $queryBuilder->expr()->isNull("t.startzeit")
                )
            );
            $queryBuilder->setParameter(":startzeitAlt",'0000-00-00 00:00:00');
        }
        if($geschlecht){
            $queryBuilder->andWhere('t.geschlecht = :geschlecht')
            ->setParameter(':geschlecht', $geschlecht);
        }
        if($spalteName === "Strecke"){
            $queryBuilder->andWhere('t.strecke = :strecke')
            ->setParameter(':strecke', $spalteWertString);
        }   
        if($spalteName === "Startgruppe"){
            $queryBuilder->andWhere('t.startgruppe = :startgruppe')
            ->setParameter(':startgruppe', $spalteWertString);
        }   
        if($spalteName === "Altersklasse"){
            $queryBuilder->andWhere('t.altersklasse = :altersklasse')
            ->setParameter(':altersklasse', $spalteWertString);
        }  
        if($spalteName === "Id"){
            if($spalteWertString !== null){
                $queryBuilder->andWhere('t.id = :id')
                ->setParameter(':id', $spalteWertString);
            }
            if($spalteWertArray !== null){
                $queryBuilder->andWhere($queryBuilder->expr()->in('t.id', ':idList'))
                ->setParameter(':idList', $spalteWertArray);
            }
        }   
        $test = $queryBuilder->getQuery();
        //returns number of affected rows
        return $queryBuilder->getQuery()->execute();
    }

    
    public function updateStatus(int $status, ?string $geschlecht = null ,?string $spalteName = null, ?string $spalteWertString = null, ?array $spalteWertArray = null): int
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->update(EA_Starter::class, 't')
        ->set('t.status', ':status')
        ->setParameter(":status",$status)
        ->where('t.status < :status2 ')
        ->setParameter(":status2",$status);

        if($geschlecht){
            $queryBuilder->andWhere('t.geschlecht = :geschlecht')
            ->setParameter(':geschlecht', $geschlecht);
        }
        if($spalteName === "Strecke"){
            $queryBuilder->andWhere('t.strecke = :strecke')
            ->setParameter(':strecke', $spalteWertString);
        }   
        if($spalteName === "Startgruppe"){
            $queryBuilder->andWhere('t.startgruppe = :startgruppe')
            ->setParameter(':startgruppe', $spalteWertString);
        }   
        if($spalteName === "Altersklasse"){
            $queryBuilder->andWhere('t.altersklasse = :altersklasse')
            ->setParameter(':altersklasse', $spalteWertString);
        }  
        if($spalteName === "Id"){
            if($spalteWertString !== null){
                $queryBuilder->andWhere('t.id = :id')
                ->setParameter(':id', $spalteWertString);
            }
            if($spalteWertArray !== null){
                $queryBuilder->andWhere($queryBuilder->expr()->in('t.id', ':idList'))
                ->setParameter(':idList', $spalteWertArray);
            }
        }   
        //returns number of affected rows
        return $queryBuilder->getQuery()->execute();
    }

    public function berechneStati(): void
    {
        //1. Prüfe wer eine gültige Buchung hat aber nicht Status 90
        $teilnehmerListarray = $this->loadTeilnehmerFalscherStatus90();
        if (is_array($teilnehmerListarray) && count($teilnehmerListarray) > 0) {
            $this->updateStatus(EA_Starter::STATUS_GUELTIGE_BUCHUNG, null, 'id', null, $teilnehmerListarray);
        }
        //2. Prüfe wer im Cache steht hat aber nicht Status 70  ist
        $teilnehmerListarray = $this->loadTeilnehmerFalscherStatus70();
        if (is_array($teilnehmerListarray) && count($teilnehmerListarray) > 0) {
            $this->updateStatus(EA_Starter::STATUS_AUF_DER_STRECKE, null, 'id', null, $teilnehmerListarray);
        }
        //3. Prüfe wer eine Startzeit hat aber nicht Status 50
        $teilnehmerListarray = $this->loadTeilnehmerFalscherStatus50();
        if (is_array($teilnehmerListarray) && count($teilnehmerListarray) > 0) {
            $this->updateStatus(EA_Starter::STATUS_GESTARTET, null, 'id', null, $teilnehmerListarray);
        }
    }

    public function loadTeilnehmerFalscherStatus90() : ?array
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult("TeilnehmerId","a", "integer");

        $query = " SELECT TeilnehmerId FROM " . EA_Repository::TB_LOG . " ";
        $query .= " INNER JOIN " . EA_Repository::TB_TEILNEHMER . " ON " . EA_Repository::TB_TEILNEHMER . ".Id = " . EA_Repository::TB_LOG . ".TeilnehmerId ";
        $query .= " WHERE Status < ".EA_Starter::STATUS_GUELTIGE_BUCHUNG."  AND Timestamp > UNIX_TIMESTAMP(Startzeit) AND  geloescht = 0 ";
        $query .= " GROUP BY TeilnehmerId ORDER BY TeilnehmerId ASC;";
        
        $query = $this->entityManager->createNativeQuery($query, $rsm);
        return array_column($query->getScalarResult(), "a"); ;
    }

        //Prüfe wer im Cache steht hat aber nicht Status 70  ist
        public function loadTeilnehmerFalscherStatus70(): ?array
        {
            $rsm = new ResultSetMapping();
            $rsm->addScalarResult("Id","a", "integer");
    
            //Prüfe wer eine gültige Buchung hat aber nicht Status 90
            $query = " SELECT " . EA_Repository::TB_TEILNEHMER . ".Id FROM " . EA_Repository::TB_TEILNEHMER . " ";
            $query .= " INNER JOIN " . EA_Repository::TB_TRANSPONDER . " ON " . EA_Repository::TB_TEILNEHMER . ".Transponder = " . EA_Repository::TB_TRANSPONDER . ".Transpondernummer ";
            $query .= " INNER JOIN " . EA_Repository::TB_CACHE . " ON " . EA_Repository::TB_TRANSPONDER . ".Transponderschluessel = " . EA_Repository::TB_CACHE . ".Transponderschluessel ";
            $query .= " WHERE Status < ".EA_Starter::STATUS_AUF_DER_STRECKE." ";
            $query .= " GROUP BY " . EA_Repository::TB_TEILNEHMER . ".Id   ORDER BY " . EA_Repository::TB_TEILNEHMER . ".Id  ASC;";

            $query = $this->entityManager->createNativeQuery($query, $rsm);
            return array_column($query->getScalarResult(), "a"); ;
        }
            
    
        //Prüfe wer eine Startzeit hat aber nicht Status 50 ist
    public function loadTeilnehmerFalscherStatus50(): ?array
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult("Id","a", "integer");

        //Prüfe wer eine gültige Buchung hat aber nicht Status 90
        $query = " SELECT " . EA_Repository::TB_TEILNEHMER . ".Id FROM " . EA_Repository::TB_TEILNEHMER . " ";
        $query .= " WHERE Status < ".EA_Starter::STATUS_GESTARTET." AND Startzeit != '0000-00-00 00:00:00'  ";
        $query .= " ORDER BY " . EA_Repository::TB_TEILNEHMER . ".Id  ASC;";

        $query = $this->entityManager->createNativeQuery($query, $rsm);
        return array_column($query->getScalarResult(), "a"); ;
    }

    public function loadStatiVerteilung(): ?array
    {
        //Die StatusIds als neues Array bauen, sodass auch leere Stati in dem Ergebnisarray auftauchen
        $stati = array_keys(EA_Starter::STATUS_LIST);
        $stati = array_combine($stati, array_fill(0, count($stati), 0));
        
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('s.id AS streckeId, t.status, count(t.id) AS anzahl')
            ->from(EA_Starter::class, 't')
            ->join(EA_Distance::class, 's') 
            ->groupBy('s.id')
            ->addGroupBy('t.status')
            ->orderBy('s.id')
            ->addOrderBy('t.status');
         $result = $queryBuilder->getQuery()->getScalarResult();

            $new_array = [];
            foreach ($result as $row) {
                $new_array[$row['streckeId']] = $stati;
            }
            foreach ($result as $row) {
                $new_array[$row['streckeId']][$row['status']] = $row['anzahl'];
            }

        return $new_array;
    }

    public function loadStreckenTeilnehmerVerteilung(): ?array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('t.geschlecht, s.id AS streckeId, s.bezLang, count(t.id) AS anzahl')
            ->from(EA_Starter::class, 't')
            ->innerJoin(EA_Distance::class, 's','WITH', 's.id = t.strecke')
            ->groupBy('s.id')
            ->addGroupBy('t.geschlecht')
            ->orderBy('s.bezLang')
            ->addOrderBy('t.geschlecht');
        $result = $queryBuilder->getQuery()->getScalarResult();
        $new_array = [];
        foreach ($result as $row) {
            $new_array[$row['streckeId']]['Bezeichnung'] = $row['bezLang'];
            $new_array[$row['streckeId']]['Summe'] = (isset($new_array[$row['streckeId']]['Summe'])) ? $new_array[$row['streckeId']]['Summe'] + $row['anzahl'] : $row['anzahl'];
            $new_array[$row['streckeId']][$row['geschlecht']] = $row['anzahl'];
        }
        return $new_array;
    }

    public function loadStreckenAltersklassenTeilnehmerVerteilung(?bool $groupby = null, ?int $streckenId = null, ?int $akId = null): ?array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('t.geschlecht, a.id AS altersklasseId, s.id AS streckeId, s.bezLang, a.altersklasse, count(t.id) AS anzahl')
            ->from(EA_Starter::class, 't')
            ->innerJoin(EA_Distance::class, 's','WITH', 's.id = t.strecke')
            ->innerJoin(EA_AgeGroup::class, 'a','WITH', 'a.id = t.altersklasse');
            if ($streckenId) {
                $queryBuilder->where('s.id = :streckenid')
                ->setParameter(":streckenid", $streckenId);   
            }    
            if ($akId) {
                $queryBuilder->where('a.id = :altersklasseId')
                ->setParameter(":altersklasseId", $akId);   
            } 
            if ($groupby) {
                $queryBuilder->groupBy('a.id')
                ->addGroupBy('t.geschlecht')
                ->addGroupBy('s.id');
            } else{
                $queryBuilder->groupBy('s.id')
                ->addGroupBy('a.id')
                ->addGroupBy('t.geschlecht');
            }
        $queryBuilder    
            ->orderBy('s.bezLang')
            ->addOrderBy('a.altersklasse');
        $result = $queryBuilder->getQuery()->getScalarResult();
        $new_array = [];

                foreach ($result as $row) {
                    if ($groupby) {
                        $new_array[$row['altersklasseId']]['Bezeichnung'] = $row['altersklasse'];
                        $new_array[$row['altersklasseId']]['Anzahl'] = (!isset($new_array[$row['altersklasseId']]['Anzahl'])) ? 0 : $new_array[$row['altersklasseId']]['Anzahl'];
                        $new_array[$row['altersklasseId']]['Anzahl'] += $row['anzahl'];
                        $new_array[$row['altersklasseId']][$row['geschlecht']][$row['streckeId']]['Bezeichnung'] = $row['bezLang'];
                        $new_array[$row['altersklasseId']][$row['geschlecht']][$row['streckeId']]['Anzahl'] = $row['anzahl'];
                    } else {
                        $new_array[$row['streckeId']]['Bezeichnung'] = $row['bezLang'];
                        $new_array[$row['streckeId']]['Anzahl'] = (!isset($new_array[$row['streckeId']]['Anzahl'])) ? 0 : $new_array[$row['streckeId']]['Anzahl'];
                        $new_array[$row['streckeId']]['Anzahl'] += $row['anzahl'];
                        $new_array[$row['streckeId']]['Unterteilung'][$row['altersklasseId']]['Bezeichnung'] = $row['altersklasse'];
                        $new_array[$row['streckeId']]['Unterteilung'][$row['altersklasseId']]['Anzahl'] = (!isset($new_array[$row['streckeId']]['Unterteilung'][$row['altersklasseId']]['Anzahl'])) ? 0 : $new_array[$row['streckeId']]['Unterteilung'][$row['altersklasseId']]['Anzahl'];
                        $new_array[$row['streckeId']]['Unterteilung'][$row['altersklasseId']]['Anzahl'] += $row['anzahl'];
                        $new_array[$row['streckeId']]['Unterteilung'][$row['altersklasseId']]['Unterteilung'][$row['geschlecht']] = $row['anzahl'];
                    }
                
              
                }
                return $new_array;
    }


    public function getDataCheckWrongStrecke(): ?array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('t')
            ->from(EA_Starter::class, 't')
            ->where('t.strecke IS NULL');
          
        return $queryBuilder->getQuery()->getResult();
    }

    public function getDataCheckWrongAltersklasse(): ?array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('t')
            ->from(EA_Starter::class, 't')
            ->where('t.altersklasse IS NULL')
            ->orWhere('t.geburtsdatum = 0000-00-00');
          
        return $queryBuilder->getQuery()->getResult();
    }

    public function getDataCheckWrongStartzeit1(): ?array
    {
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult(EA_Starter::class,"t");

        $query = "SELECT t.* FROM " . EA_Repository::TB_TEILNEHMER . " t WHERE t.Startzeit > NOW() ;";
  
        $query = $this->entityManager->createNativeQuery($query, $rsm);
        return $query->getResult();
    }

    public function getDataCheckWrongTransponder(): ?array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('i')
            ->from(EA_Hit::class, 'i')
            ->where('i.teilnehmer  IS NULL')
            ->andWhere('i.geloescht = 0')
            ->groupBy('i.transponderId')
            ->addGroupBy('i.leser');
          
        return $queryBuilder->getQuery()->getResult();
    }


    public function getDataCheckWrongStartzeit2(): ?array
    {
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult(EA_Starter::class,"t");

        $query = "SELECT t.* FROM " . EA_Repository::TB_TEILNEHMER . " t
        LEFT JOIN " . EA_Repository::TB_LOG . " i ON i.TeilnehmerId = t.Id 
        WHERE t.Startzeit != '0000-00-00 00:00:00' 
        AND i.Timestamp < UNIX_TIMESTAMP(t.Startzeit) 
        AND i.geloescht = 0 
        GROUP BY t.Id ;";
  
        $query = $this->entityManager->createNativeQuery($query, $rsm);
        return $query->getResult();
    }


}
