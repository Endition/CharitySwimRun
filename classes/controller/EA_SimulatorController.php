<?php
namespace CharitySwimRun\classes\controller;

use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use CharitySwimRun\classes\model\EA_Hit;
use CharitySwimRun\classes\model\EA_Team;
use CharitySwimRun\classes\model\EA_Simulator;
use CharitySwimRun\classes\model\EA_Starter;
use CharitySwimRun\classes\model\EA_Club;

class EA_SimulatorController extends EA_Controller
{
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function createRandomTeilnehmer(): array
    {
        $teilnehmerlist = $this->EA_StarterRepository->loadList();
        $EA_Simulator = new EA_Simulator();
        $messages = [];
       
        $anzahlTeilnehmer = count($teilnehmerlist);
         //10% Chance to create a new TN
        if($anzahlTeilnehmer < 20 || rand(0,$anzahlTeilnehmer) < ($anzahlTeilnehmer/10)){
            $vereinszufall = rand(0,100);
            $mannschaftzufall = rand(0,100);
        
            $streckeList = $this->EA_DistanceRepository->loadList();
            $newTeilnehmer = new EA_Starter();
            $newTeilnehmer->setName($EA_Simulator->lastname[array_rand($EA_Simulator->lastname)]);
            $newTeilnehmer->setVorname($EA_Simulator->firstname[array_rand($EA_Simulator->firstname)]);
            $newTeilnehmer->setStartnummer(rand(1000,100000));
            $newTeilnehmer->setTransponder($newTeilnehmer->getStartnummer());
            $geburtsdatum = new DateTimeImmutable();
            $geburtsdatum = $geburtsdatum->setTimestamp(mt_rand(strtotime("90 years ago"),strtotime("1 years ago")));
            $newTeilnehmer->setGeburtsdatum($geburtsdatum);
            $newTeilnehmer->setGeschlecht(EA_Starter::GESCHLECHT_LIST_KURZ[array_rand(EA_Starter::GESCHLECHT_LIST_KURZ)]);
            $newTeilnehmer->setStrecke($streckeList[array_rand($streckeList)]);
            $newTeilnehmer->setStartzeit(new DateTimeImmutable());
        
            //in 50% no club
            if($vereinszufall < 50){
                $newTeilnehmer->setVerein(null);
            }
            //in 40% existing club
            if($vereinszufall > 50 && $vereinszufall < 90){
                $vereinList = $this->EA_ClubRepository->loadList();
                if(count($vereinList) > 0){
                    $newTeilnehmer->setVerein($vereinList[array_rand($vereinList)]);
                }
            }
            //in 10% create new Club
            if($vereinszufall > 90){
                $newVereinBez = $EA_Simulator->fiktiveVereine[array_rand($EA_Simulator->fiktiveVereine)];
                $verein = $this->vereinRepository->loadByBezeichnung($newVereinBez);
                if($verein === null){
                    $verein = new EA_Club();
                    $verein->setVerein($newVereinBez);
                    $this->EA_ClubRepository->create($verein);
                }
  
                $newTeilnehmer->setVerein($verein);
                $messages[] =  "Verein {$verein->getVerein()} angelegt";
            }
        
             //in 50% no team
            if($mannschaftzufall < 50){
                $newTeilnehmer->setMannschaft(null);
            }
        
             //in 40% existing team
            if($mannschaftzufall > 50 && $mannschaftzufall < 90){
                $mannschaftList = $this->EA_TeamRepository->loadList();
                if(count($mannschaftList) > 0){
                    $newTeilnehmer->setMannschaft($mannschaftList[array_rand($mannschaftList)]);
                }
            }
        
             //in 10% create new team
            if($mannschaftzufall > 90){
                $mannschaftsKategorieList = $this->EA_TeamCategoryRepository->loadList();
                $mannschaft = new EA_Team();
                $mannschaft->setStartnummer(rand(1,20000));
                $mannschaft->setMannschaftskategorie($mannschaftsKategorieList[array_rand($mannschaftsKategorieList)]);
                $mannschaft->setVer_name($EA_Simulator->lastname[array_rand($EA_Simulator->lastname)]);
                $mannschaft->setVer_vorname($EA_Simulator->firstname[array_rand($EA_Simulator->firstname)]);
                $mannschaft->setMannschaft($EA_Simulator->fiktiveMannschaften[array_rand($EA_Simulator->fiktiveMannschaften)]);
                $this->EA_TeamRepository->create($mannschaft);
                $newTeilnehmer->setMannschaft($mannschaft);
                $messages[] =  "Mannschaft {$mannschaft->getMannschaft()} angelegt";
        
            }
        
            $newTeilnehmer->setAltersklasse($this->EA_AgeGroupRepository->findByGeburtsjahr($geburtsdatum));
            $newTeilnehmer->setKonfiguration($this->EA_ConfigurationRepository->load());
            $this->EA_StarterRepository->create($newTeilnehmer);  
            $messages[] =  "neuen Teilnehmer {$newTeilnehmer->getGesamtname()} angelegt";
        }
        $this->createRandomImpuls($messages, $teilnehmerlist);
        return $messages;
    }

    public function createRandomImpuls(&$messages, array $teilnehmerList): void
    {
        $teilnehmerZufall = $teilnehmerList[array_rand($teilnehmerList)];
        $impuls = new EA_Hit();
        $impuls->setTimestamp(time());
        $impuls->setTeilnehmer($teilnehmerZufall);
        $impuls->setLeser(rand(1,5));
        $this->EA_HitRepository->create($impuls);
        $messages[] =  "Zufälligen Impuls für einen Teilnehmer {$teilnehmerZufall->getGesamtname()} mit der Startnummer {$teilnehmerZufall->getStartnummer()} erzeugt";
    }

}