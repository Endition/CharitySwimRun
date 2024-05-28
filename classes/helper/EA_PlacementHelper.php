<?php
namespace CharitySwimRun\classes\helper;

use DateTimeImmutable;
use Doctrine\ORM\EntityManager;

use CharitySwimRun\classes\model\EA_SpecialEvaluation;
use CharitySwimRun\classes\model\EA_StarterRepository;
use CharitySwimRun\classes\model\EA_ConfigurationRepository;
use CharitySwimRun\classes\model\EA_HitRepository;

class EA_PlacementHelper
{
    private EA_StarterRepository $EA_StarterRepository;
    private EA_ConfigurationRepository $EA_ConfigurationRepository;   
    private EA_HitRepository $EA_HitRepository;   

    public function __construct(EntityManager $entityManager)
    {
        $this->EA_StarterRepository = new EA_StarterRepository($entityManager);
        $this->EA_ConfigurationRepository = new EA_ConfigurationRepository($entityManager);
        $this->EA_HitRepository = new EA_HitRepository($entityManager);

    }

    /**
     * Do not calculate result over and over again, when event has ended 
     */
    private function checkIfCalculationIsNecassary(): bool
    {
        $konfiguration = $this->EA_ConfigurationRepository->load();
        $numberOfImpulse =  $this->EA_HitRepository->getNumberOfEntries();
        $result = false;

        if($numberOfImpulse > $konfiguration->getLastCalculationResultsNumber()){
            $result = true;
        }
        
        $konfiguration->setLastCalculationResultsNumber($numberOfImpulse);
        $this->EA_ConfigurationRepository->update();
        return $result;
    }

    /**
     * calculates placement in age group and distance
     * need $teilnehmerList sortet by impulse
     */
    public function berechnePlatzierung(array $teilnehmerListSorted): array
    {
        if($this->checkIfCalculationIsNecassary() === false){
            return $teilnehmerListSorted;
        }
        
        //sort list by impuls, not necassary anymore 24.05.2024
       # $teilnehmerListSorted = $teilnehmerList; # $this->quicksort($teilnehmerList);
        $platzList = [];
        $plus1 = 1;

        foreach($teilnehmerListSorted as $teilnehmer){
            $streckeId = $teilnehmer->getStrecke()->getId();
            $geschlecht = $teilnehmer->getGeschlecht();
            $altersklasseId = $teilnehmer->getAltersklasse()->getId();
            $teilnehmerImpulse = $teilnehmer->getImpulse();
            //Ids for Arrays
            $g = $geschlecht;
            $sgId = $streckeId.$geschlecht;
            $sagId = $streckeId.$altersklasseId.$geschlecht;
            
            //Gesamt
            //if array element does not exist -> create. Important for 1. place
            if(!isset($platzList['gesamt'][$g]['place'])){
                $platzList['gesamt'][$g]['place'] = $plus1;
                $platzList['gesamt'][$g]['impulseCount'][$plus1] = 0;
            }else{
                //check if impulse from the starter beforre und this starter are the same. If true -> same place. Do not count up
                if($teilnehmerImpulse < $platzList['gesamt'][$g]['impulseCount'][$platzList['gesamt'][$g]['place']] || $platzList['gesamt'][$g]['impulseCount'][$platzList['gesamt'][$g]['place']] === 0){
                    $platzList['gesamt'][$g]['place'] = $platzList['gesamt'][$g]['place'] + $plus1;
                }
                //save impulse for the comparision in next loop
                $platzList['gesamt'][$g]['impulseCount'][$platzList['gesamt'][$g]['place']] = $teilnehmerImpulse ;
            }

            //Strecken
            if(!isset($platzList['strecke'][$sgId]['place'])){
                $platzList['strecke'][$sgId]['place'] = $plus1;
                $platzList['strecke'][$sgId]['impulseCount'][$plus1] = 0;
            }else{
                if($teilnehmerImpulse < $platzList['strecke'][$sgId]['impulseCount'][$platzList['strecke'][$sgId]['place']] || $platzList['strecke'][$sgId]['impulseCount'][$platzList['strecke'][$sgId]['place']]  === 0){
                    $platzList['strecke'][$sgId]['place'] = $platzList['strecke'][$sgId]['place'] + $plus1;
                }
                $platzList['strecke'][$sgId]['impulseCount'][$platzList['strecke'][$sgId]['place']] = $teilnehmerImpulse ;
            }

            //Altersklassen
            if(!isset($platzList['altersklasse'][$sagId]['place'])){
                $platzList['altersklasse'][$sagId]['place'] = $plus1;
                $platzList['altersklasse'][$sagId]['impulseCount'][$plus1] = 0;
            }else{
                if($teilnehmerImpulse < $platzList['altersklasse'][$sagId]['impulseCount'][$platzList['altersklasse'][$sagId]['place']] || $platzList['altersklasse'][$sagId]['impulseCount'][$platzList['altersklasse'][$sagId]['place']]=== 0){
                    $platzList['altersklasse'][$sagId]['place'] = $platzList['altersklasse'][$sagId]['place'] + $plus1;
                }
                $platzList['altersklasse'][$sagId]['impulseCount'][$platzList['altersklasse'][$sagId]['place']] = $teilnehmerImpulse ;

            }
            //save the placess 
            $teilnehmer->setGesamtplatz($platzList['gesamt'][$g]['place']);
            $teilnehmer->setStreckenplatz( $platzList['strecke'][$sgId]['place']);
            $teilnehmer->setAkplatz($platzList['altersklasse'][$sagId]['place']);
        }
        $this->EA_StarterRepository->update();
        return $teilnehmerListSorted;
    }

    public function quicksort(array $unsortedList, string $funcName = "getImpulse", ?EA_SpecialEvaluation $specialEvaluation = null)
    {
        //This method can be called directly, update cache necassary
        $this->EA_HitRepository->updateImpulseCache();
        
        $params = [$specialEvaluation];
            usort($unsortedList, function($a, $b) use ($funcName, $params) {
                $valueA = call_user_func_array([$a, $funcName], $params);
                $valueB = call_user_func_array([$b, $funcName], $params);
        
                if ($valueA == $valueB) {
                    return 0;
                }
                return ($valueA > $valueB) ? -1 : 1;
            });
        return $unsortedList;
    }
}