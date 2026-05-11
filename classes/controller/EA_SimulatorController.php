<?php

namespace CharitySwimRun\classes\controller;

use CharitySwimRun\classes\model\EA_Starter;
use CharitySwimRun\classes\model\EA_Club;
use CharitySwimRun\classes\model\EA_Team;
use CharitySwimRun\classes\model\EA_Hit;
use CharitySwimRun\classes\model\EA_Cache;
use CharitySwimRun\classes\model\EA_Simulator;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;

/**
 * Controller for simulating race events and participant behavior.
 */
class EA_SimulatorController extends EA_Controller
{
    private EA_Simulator $simulatorData;

    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager);
        $this->simulatorData = new EA_Simulator();
    }

    /**
     * Orchestrates the simulation by triggering different random events.
     * 
     * @param string $mode The simulation mode: 'log' (direct hit) or 'cache' (RFID trigger).
     * @return array List of messages describing the actions taken.
     */
    public function createRandomTeilnehmer(string $mode = 'log'): array
    {
        $messages = [];

        // 1. Participant Creation (5% chance)
        $this->simulateNewParticipant($messages);

        // 2. Transponder Returns (2% chance)
        $this->simulateTransponderReturn($messages);

        // 3. Fehlbuchungen / Unknown Scans (5% chance, only in cache mode)
        if ($mode === 'cache') {
            $this->simulateFehlbuchung($messages);
        }

        // 4. Regular Hits / Scans (Always tries to pick one active participant)
        $this->simulateActiveHit($messages, $mode);

        return $messages;
    }

    /**
     * Logic for creating a new participant if transponders are available.
     */
    private function simulateNewParticipant(array &$messages): void
    {
        $anzahlAktiv = $this->EA_StarterRepository->getAnzahlTeilnehmer();
        
        // 5% chance or if very few participants exist
        if ($anzahlAktiv >= 20 && rand(1, 100) > 5) {
            return;
        }

        $availableIds = $this->getAvailableTransponderIds();
        if (empty($availableIds)) {
            $messages[] = "SIMULATION: Kein freier Transponder mehr vorhanden - Erstellung abgebrochen.";
            return;
        }

        $newTeilnehmer = new EA_Starter();
        $randomId = $availableIds[array_rand($availableIds)];
        $newTeilnehmer->setTransponder($randomId);
        $newTeilnehmer->setStartnummer($randomId);

        // Identity
        $newTeilnehmer->setName($this->simulatorData->lastname[array_rand($this->simulatorData->lastname)]);
        $newTeilnehmer->setVorname($this->simulatorData->firstname[array_rand($this->simulatorData->firstname)]);
        
        $geburtsdatum = (new DateTimeImmutable())->setTimestamp(mt_rand(strtotime("90 years ago"), strtotime("15 years ago")));
        $newTeilnehmer->setGeburtsdatum($geburtsdatum);
        $newTeilnehmer->setGeschlecht(EA_Starter::GESCHLECHT_LIST_KURZ[array_rand(EA_Starter::GESCHLECHT_LIST_KURZ)]);
        
        $streckeList = $this->EA_DistanceRepository->loadList();
        $newTeilnehmer->setStrecke($streckeList[array_rand($streckeList)]);
        $newTeilnehmer->setStartzeit(new DateTimeImmutable());
        $newTeilnehmer->setKonfiguration($this->konfiguration);
        $newTeilnehmer->setAltersklasse($this->EA_AgeGroupRepository->findByGeburtsjahr($geburtsdatum));
        $newTeilnehmer->setStatus(EA_Starter::STATUS_STARTUNTERLAGEN_ABHEHOLT);

        $this->assignRandomClubAndTeam($newTeilnehmer, $messages);

        $this->EA_StarterRepository->create($newTeilnehmer);
        $messages[] = "NEUANLAGE: {$newTeilnehmer->getGesamtname()} (StNr: {$newTeilnehmer->getStartnummer()}) angemeldet.";
    }

    /**
     * Logic for simulating a participant returning their hardware.
     */
    private function simulateTransponderReturn(array &$messages): void
    {
        if (rand(1, 100) <= 2) {
            $activeParticipant = $this->EA_StarterRepository->loadRandomTeilnehmer();
            if ($activeParticipant) {
                $activeParticipant->setStatus(EA_Starter::STATUS_TRANSPONDER_ZURUECKGEGEBEN);
                $this->entityManager->flush();
                $messages[] = "RÜCKGABE: {$activeParticipant->getGesamtname()} hat Transponder zurückgegeben.";
            }
        }
    }

    /**
     * Simulates a scan of a chip that is not assigned to an active participant.
     */
    private function simulateFehlbuchung(array &$messages): void
    {
        if (rand(1, 100) > 5) {
            return;
        }

        $allChips = $this->entityManager->createQueryBuilder()
            ->select('r.Transpondernummer, r.Transponderschluessel')
            ->from(\CharitySwimRun\classes\model\EA_RfidChip::class, 'r')
            ->getQuery()->getScalarResult();

        $activeTransponders = $this->entityManager->createQueryBuilder()
            ->select('t.transponder')->from(EA_Starter::class, 't')
            ->where('t.status < :s')->setParameter('s', EA_Starter::STATUS_TRANSPONDER_ZURUECKGEGEBEN)
            ->getQuery()->getScalarResult();
        
        $activeIds = array_column($activeTransponders, 'transponder');
        
        $inactiveChips = array_filter($allChips, function($c) use ($activeIds) {
            return !in_array($c['Transpondernummer'], $activeIds);
        });

        if (!empty($inactiveChips)) {
            $chip = $inactiveChips[array_rand($inactiveChips)];
            $this->pushToCache($chip['Transponderschluessel'], rand(1, 5));
            $messages[] = "FEHLBUCHUNG: Inaktiver Chip gescannt (Nr: {$chip['Transpondernummer']})";
        }
    }

    /**
     * Logic for a regular scan of an active participant.
     */
    private function simulateActiveHit(array &$messages, string $mode): void
    {
        $teilnehmer = $this->EA_StarterRepository->loadRandomTeilnehmer();
        if ($teilnehmer) {
            $this->createRandomImpuls($messages, $teilnehmer, $mode);
        }
    }

    /**
     * Finds physical transponder IDs that are currently not assigned to active participants.
     */
    private function getAvailableTransponderIds(): array
    {
        $all = $this->entityManager->createQueryBuilder()
            ->select('r.Transpondernummer')->from(\CharitySwimRun\classes\model\EA_RfidChip::class, 'r')
            ->getQuery()->getScalarResult();
        $allIds = array_column($all, 'Transpondernummer');

        $used = $this->entityManager->createQueryBuilder()
            ->select('t.transponder')->from(EA_Starter::class, 't')
            ->where('t.transponder IS NOT NULL')
            ->andWhere('t.status < :s')->setParameter('s', EA_Starter::STATUS_TRANSPONDER_ZURUECKGEGEBEN)
            ->getQuery()->getScalarResult();
        $usedIds = array_column($used, 'transponder');

        return array_diff($allIds, $usedIds);
    }

    /**
     * Helper to assign clubs or teams based on probability.
     */
    private function assignRandomClubAndTeam(EA_Starter $teilnehmer, array &$messages): void
    {
        $vProb = rand(1, 100);
        if ($vProb > 50 && $vProb < 90) {
            $list = $this->EA_ClubRepository->loadList();
            if (!empty($list)) {
                $teilnehmer->setVerein($list[array_rand($list)]);
            }
        } elseif ($vProb >= 90) {
            $name = $this->simulatorData->fiktiveVereine[array_rand($this->simulatorData->fiktiveVereine)];
            $verein = $this->EA_ClubRepository->loadByBezeichnung($name);
            if ($verein === null) {
                $verein = new EA_Club();
                $verein->setVerein($name);
                $this->EA_ClubRepository->create($verein);
                $messages[] = "Verein {$name} angelegt";
            }
            $teilnehmer->setVerein($verein);
        }

        $mProb = rand(1, 100);
        if ($mProb > 50 && $mProb < 90) {
            $list = $this->EA_TeamRepository->loadList();
            if (!empty($list)) {
                $teilnehmer->setMannschaft($list[array_rand($list)]);
            }
        } elseif ($mProb >= 90) {
            $cat = $this->EA_TeamCategoryRepository->loadList();
            $team = new EA_Team();
            $team->setStartnummer(rand(1, 20000));
            $team->setMannschaftskategorie($cat[array_rand($cat)]);
            $team->setVer_name($this->simulatorData->lastname[array_rand($this->simulatorData->lastname)]);
            $team->setVer_vorname($this->simulatorData->firstname[array_rand($this->simulatorData->firstname)]);
            $team->setMannschaft($this->simulatorData->fiktiveMannschaften[array_rand($this->simulatorData->fiktiveMannschaften)]);
            $this->EA_TeamRepository->create($team);
            $teilnehmer->setMannschaft($team);
            $messages[] = "Mannschaft {$team->getMannschaft()} angelegt";
        }
    }

    /**
     * Pushes a raw scan entry to the cache table.
     */
    private function pushToCache(string $key, int $reader, int $delay = 0): void
    {
        $cache = new EA_Cache();
        $cache->setTransponderschluessel($key);
        $cache->setBuchungszeit(time() + $delay);
        $cache->setLeser($reader);
        $this->entityManager->persist($cache);
        $this->entityManager->flush();
    }

    /**
     * Simulates an RFID scan or a manual hit.
     */
    public function createRandomImpuls(array &$messages, EA_Starter $teilnehmer, string $mode = 'log'): void
    {
        if ($mode === 'cache') {
            $tp = $teilnehmer->getTransponder();
            $chip = $tp ? $this->entityManager->getRepository(\CharitySwimRun\classes\model\EA_RfidChip::class)->find($tp) : null;

            if ($chip) {
                $this->pushToCache($chip->getTransponderschluessel(), rand(1, 5));
                $messages[] = "SIMULIERT: RFID-Scan (Key: {$chip->getTransponderschluessel()})";

                // Robustness test: duplicates
                if (rand(1, 100) <= 20) {
                    $lockout = $this->konfiguration->getBuchungssperre();
                    for ($i = 1; $i <= rand(1, 4); $i++) {
                        $this->pushToCache($chip->getTransponderschluessel(), rand(1, 5), rand(0, max(0, $lockout - 1)));
                        $messages[] = "TEST: Doppel-Scan {$i} gesendet.";
                    }
                }
            } else {
                $mode = 'log'; // Fallback
            }
        }

        if ($mode === 'log') {
            $impuls = new EA_Hit();
            $impuls->setTimestamp(time());
            $impuls->setTeilnehmer($teilnehmer);
            $impuls->setLeser(rand(1, 5));
            $this->EA_HitRepository->create($impuls);
            $messages[] = "MANUELL: Impuls für {$teilnehmer->getGesamtname()} erzeugt.";
        }
    }
}