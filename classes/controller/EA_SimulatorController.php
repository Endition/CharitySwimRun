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

    /**
     * Creates a random participant or hit based on the specified mode.
     * 
     * @param string $mode The simulation mode: 'log' (direct hit) or 'cache' (RFID trigger).
     * @return array List of messages describing the actions taken.
     */
    public function createRandomTeilnehmer(string $mode = 'log'): array
    {
        $anzahlTeilnehmer = $this->EA_StarterRepository->getAnzahlTeilnehmer();
        $EA_Simulator = new EA_Simulator();
        $messages = [];

        // 10% chance to create a new participant
        if ($anzahlTeilnehmer < 20 || rand(0, $anzahlTeilnehmer) < ($anzahlTeilnehmer / 5)) {
            $vereinszufall = rand(0, 100);
            $mannschaftzufall = rand(0, 100);

            $streckeList = $this->EA_DistanceRepository->loadList();
            $newTeilnehmer = new EA_Starter();

            // Get available transponder IDs from the 'box' (database table transponder)
            $allTransponders = $this->entityManager->createQueryBuilder()
                ->select('r.Transpondernummer')
                ->from(\CharitySwimRun\classes\model\EA_RfidChip::class, 'r')
                ->getQuery()
                ->getScalarResult();
            $transponderIds = array_column($allTransponders, 'Transpondernummer');

            // Find transponders that are not yet assigned to an active participant
            $usedTransponders = $this->entityManager->createQueryBuilder()
                ->select('t.transponder')
                ->from(EA_Starter::class, 't')
                ->where('t.transponder IS NOT NULL')
                ->getQuery()
                ->getScalarResult();
            $usedIds = array_column($usedTransponders, 'transponder');

            $availableIds = array_diff($transponderIds, $usedIds);

            if (count($availableIds) > 0) {
                $randomId = $availableIds[array_rand($availableIds)];
                $newTeilnehmer->setTransponder($randomId);
                $newTeilnehmer->setStartnummer($randomId);
            } else {
                // Fallback to random high number if no physical transponders are left
                $newTeilnehmer->setStartnummer(rand(1000, 2000));
                $newTeilnehmer->setTransponder($newTeilnehmer->getStartnummer());
            }

            $newTeilnehmer->setName($EA_Simulator->lastname[array_rand($EA_Simulator->lastname)]);
            $newTeilnehmer->setVorname($EA_Simulator->firstname[array_rand($EA_Simulator->firstname)]);
            $geburtsdatum = new DateTimeImmutable();
            $geburtsdatum = $geburtsdatum->setTimestamp(mt_rand(strtotime("90 years ago"), strtotime("1 years ago")));
            $newTeilnehmer->setGeburtsdatum($geburtsdatum);
            $newTeilnehmer->setGeschlecht(EA_Starter::GESCHLECHT_LIST_KURZ[array_rand(EA_Starter::GESCHLECHT_LIST_KURZ)]);
            $newTeilnehmer->setStrecke($streckeList[array_rand($streckeList)]);
            $newTeilnehmer->setStartzeit(new DateTimeImmutable());

            // Assign club based on probability
            if ($vereinszufall > 50 && $vereinszufall < 90) {
                $vereinList = $this->EA_ClubRepository->loadList();
                if (count($vereinList) > 0) {
                    $newTeilnehmer->setVerein($vereinList[array_rand($vereinList)]);
                }
            } elseif ($vereinszufall >= 90) {
                $newVereinBez = $EA_Simulator->fiktiveVereine[array_rand($EA_Simulator->fiktiveVereine)];
                $verein = $this->EA_ClubRepository->loadByBezeichnung($newVereinBez);
                if ($verein === null) {
                    $verein = new EA_Club();
                    $verein->setVerein($newVereinBez);
                    $this->EA_ClubRepository->create($verein);
                }
                $newTeilnehmer->setVerein($verein);
                $messages[] = "Verein {$verein->getVerein()} angelegt";
            }

            // Assign team based on probability
            if ($mannschaftzufall > 50 && $mannschaftzufall < 90) {
                $mannschaftList = $this->EA_TeamRepository->loadList();
                if (count($mannschaftList) > 0) {
                    $newTeilnehmer->setMannschaft($mannschaftList[array_rand($mannschaftList)]);
                }
            } elseif ($mannschaftzufall >= 90) {
                $mannschaftsKategorieList = $this->EA_TeamCategoryRepository->loadList();
                $mannschaft = new EA_Team();
                $mannschaft->setStartnummer(rand(1, 20000));
                $mannschaft->setMannschaftskategorie($mannschaftsKategorieList[array_rand($mannschaftsKategorieList)]);
                $mannschaft->setVer_name($EA_Simulator->lastname[array_rand($EA_Simulator->lastname)]);
                $mannschaft->setVer_vorname($EA_Simulator->firstname[array_rand($EA_Simulator->firstname)]);
                $mannschaft->setMannschaft($EA_Simulator->fiktiveMannschaften[array_rand($EA_Simulator->fiktiveMannschaften)]);
                $this->EA_TeamRepository->create($mannschaft);
                $newTeilnehmer->setMannschaft($mannschaft);
                $messages[] = "Mannschaft {$mannschaft->getMannschaft()} angelegt";
            }

            $newTeilnehmer->setAltersklasse($this->EA_AgeGroupRepository->findByGeburtsjahr($geburtsdatum));
            $newTeilnehmer->setKonfiguration($this->konfiguration);
            $this->EA_StarterRepository->create($newTeilnehmer);
            $messages[] = "neuen Teilnehmer {$newTeilnehmer->getGesamtname()} angelegt";
        }

        // Generate a random impulse for an existing participant
        $teilnehmerZufall = $this->EA_StarterRepository->loadRandomTeilnehmer();
        if ($teilnehmerZufall) {
            $this->createRandomImpuls($messages, $teilnehmerZufall, $mode);
        }

        return $messages;
    }

    /**
     * Simulates an RFID scan or a manual hit.
     * 
     * @param array $messages Reference to the message list.
     * @param EA_Starter $teilnehmerZufall The participant to record the hit for.
     * @param string $mode Simulation mode ('cache' or 'log').
     */
    public function createRandomImpuls(array &$messages, EA_Starter $teilnehmerZufall, string $mode = 'log'): void
    {
        if ($mode === 'cache') {
            try {
                $transponderNummer = $teilnehmerZufall->getTransponder();
                if (!$transponderNummer) {
                    throw new \Exception("Teilnehmer hat keinen Transponder zugewiesen.");
                }

                $rfidChip = $this->entityManager->getRepository(\CharitySwimRun\classes\model\EA_RfidChip::class)->find($transponderNummer);

                if ($rfidChip) {
                    $cache = new \CharitySwimRun\classes\model\EA_Cache();
                    $cache->setTransponderschluessel($rfidChip->getTransponderschluessel());
                    $cache->setBuchungszeit(time());
                    $cache->setLeser(rand(1, 5));
                    $this->entityManager->persist($cache);
                    $this->entityManager->flush();
                    $messages[] = "SIMULIERT: RFID-Scan in Cache geschrieben (Key: {$rfidChip->getTransponderschluessel()})";

                    // 20% chance for multiple duplicate scans to test the trigger's robustness (Buchungssperre)
                    if (rand(1, 100) <= 20) {
                        $lockout = $this->konfiguration->getBuchungssperre();
                        $numDuplicates = rand(1, 4);

                        for ($i = 0; $i < $numDuplicates; $i++) {
                            $delay = rand(0, max(0, $lockout - 1));
                            $cacheDuplicate = new \CharitySwimRun\classes\model\EA_Cache();
                            $cacheDuplicate->setTransponderschluessel($rfidChip->getTransponderschluessel());
                            $cacheDuplicate->setBuchungszeit(time() + $delay);
                            $cacheDuplicate->setLeser($cache->getLeser());
                            $this->entityManager->persist($cacheDuplicate);
                            $messages[] = "TEST: Doppel-Scan {$i} mit {$delay}s Zeitversatz gesendet (Sperre: {$lockout}s)";
                        }
                        $this->entityManager->flush();
                    }
                } else {
                    $messages[] = "FEHLER: Kein RFID-Key für Transponder {$transponderNummer} gefunden. Nutze Fallback auf Log.";
                    $mode = 'log'; // Fallback
                }
            } catch (\Throwable $t) {
                $messages[] = "CACHE-FEHLER: " . $t->getMessage() . " (Fallback auf Log)";
                $mode = 'log';
            }
        }

        if ($mode === 'log') {
            $impuls = new EA_Hit();
            $impuls->setTimestamp(time());
            $impuls->setTeilnehmer($teilnehmerZufall);
            $impuls->setLeser(rand(1, 5));
            $this->EA_HitRepository->create($impuls);
            $messages[] = "Zufälligen Impuls für Teilnehmer {$teilnehmerZufall->getGesamtname()} (StNr: {$teilnehmerZufall->getStartnummer()}) erzeugt";
        }
    }
}