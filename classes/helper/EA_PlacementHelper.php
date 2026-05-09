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
     * Calculates and persists placement rankings using a single native SQL statement with MySQL RANK() window functions.
     * This replaces the old PHP loop and is orders of magnitude faster for large participant counts.
     * After the DB update, all in-memory objects are updated to reflect the new ranks without a second DB fetch.
     */
    public function berechnePlatzierung(array $teilnehmerListSorted): array
    {
        // Ensures cache integrity before ranking, as results depend on correct impulse counts.
        $this->EA_HitRepository->updateImpulseCache();

        if ($this->checkIfCalculationIsNecassary() === false) {
            return $teilnehmerListSorted;
        }

        // Persist all three rankings with a single SQL statement using Window Functions (MySQL 8.0+).
        // HINWEIS: Diese manuelle Berechnung dient als Echtzeit-Fallback für Urkunden/Berichte,
        // damit diese immer aktuell sind, auch wenn das 3-Minuten-MySQL-Event noch nicht gelaufen ist.
        $this->EA_StarterRepository->berechnePlatzierungSQL();

        // Update the in-memory PHP objects to reflect the newly calculated ranks for the current request,
        // avoiding a second round-trip to the database.
        $platzList = [];
        $plus1 = 1;
        foreach ($teilnehmerListSorted as $teilnehmer) {
            $g      = $teilnehmer->getGeschlecht();
            $sgId   = $teilnehmer->getStrecke()->getId() . $g;
            $sagId  = $teilnehmer->getStrecke()->getId() . $teilnehmer->getAltersklasse()->getId() . $g;
            $impulse = $teilnehmer->getImpulse();

            foreach (['gesamt' => $g, 'strecke' => $sgId, 'altersklasse' => $sagId] as $category => $key) {
                if (!isset($platzList[$category][$key]['place'])) {
                    $platzList[$category][$key]['place'] = $plus1;
                    $platzList[$category][$key]['lastImpulse'] = 0;
                } elseif ($impulse < $platzList[$category][$key]['lastImpulse'] || $platzList[$category][$key]['lastImpulse'] === 0) {
                    $platzList[$category][$key]['place']++;
                }
                $platzList[$category][$key]['lastImpulse'] = $impulse;
            }

            $teilnehmer->setGesamtplatz($platzList['gesamt'][$g]['place']);
            $teilnehmer->setStreckenplatz($platzList['strecke'][$sgId]['place']);
            $teilnehmer->setAkplatz($platzList['altersklasse'][$sagId]['place']);
        }

        return $teilnehmerListSorted;
    }

    public function quicksort(array $unsortedList, string $funcName = "getImpulse", ?EA_SpecialEvaluation $specialEvaluation = null)
    {
        // Hinweis: Ein manuelles Update des ImpulseCache ist hier nicht mehr nötig, da Trigger dies in Echtzeit erledigen.
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