<?php
namespace CharitySwimRun\classes\controller;

use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use SplFileObject;

use CharitySwimRun\classes\model\EA_AgeGroupRepository;
use CharitySwimRun\classes\model\EA_ConfigurationRepository;
use CharitySwimRun\classes\model\EA_Starter;
use CharitySwimRun\classes\model\EA_Message;

class EA_ImportController extends EA_Controller
{
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function getPageImport(): string
    {
        if (isset($_POST['sendImportData']) && isset($_FILES['datei'])) {
            $datei = $_FILES['datei'];
            $trennzeichen = $_POST['trennzeichen'] ?? ';';
            $ignorieren = isset($_POST['ignorieren']) ? true : false;
            
            if ($datei['error'] !== UPLOAD_ERR_OK) {
                $this->EA_Messages->addMessage("Fehler beim Hochladen der Datei. Fehlercode: " . $datei['error'], 193534574, EA_Message::MESSAGE_ERROR);
                return $this->EA_FR->getFormImport();
            }

            $mime_types = ['text/csv', 'text/plain', 'application/csv', 'text/comma-separated-values', 'application/excel', 'application/vnd.ms-excel', 'application/vnd.msexcel'];
            $fileExtension = strtolower(pathinfo($datei['name'], PATHINFO_EXTENSION));

            if ($fileExtension !== 'csv' && !in_array($datei['type'], $mime_types)) {
                $this->EA_Messages->addMessage("Die hochgeladene Datei muss eine .csv Datei sein.", 193534574, EA_Message::MESSAGE_ERROR);
            } else {
                $successCount = 0;
                $errorCount = 0;
                $errorMessages = [];
                
                try {
                    $file = new SplFileObject($datei['tmp_name'], 'r');
                    $file->setFlags(SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);
                    $file->setCsvControl($trennzeichen);

                    $konfiguration = $this->EA_ConfigurationRepository->load();
                    
                    foreach ($file as $index => $data) {
                        // Header überspringen
                        if ($index === 0 && $ignorieren) {
                            continue;
                        }
                        
                        if (!is_array($data) || count($data) < 7) {
                            // Wahrscheinlich eine leere Zeile oder komplett fehlerhaft
                            continue;
                        }

                        try {
                            foreach ($data as &$field) {
                                // Convert ISO-8859-1/Windows-1252 from Excel to UTF-8 for the DB
                                $field = mb_convert_encoding($field, 'UTF-8', 'auto');
                            }
                            unset($field);
                            
                            $mappedData = [
                                'startnummer'  => $data[0] ?? null,
                                'transponder'  => $data[1] ?? null,
                                'vorname'      => trim($data[2] ?? ""),
                                'name'         => trim($data[3] ?? ""),
                                'geburtsdatum' => trim($data[4] ?? ""),
                                'geschlecht'   => trim($data[5] ?? ""),
                                'strecke'      => trim($data[6] ?? ""),
                                'startgruppe'  => $data[7] ?? 0,
                                'plz'          => $data[8] ?? null,
                                'ort'          => $data[9] ?? null,
                                'strasse'      => $data[10] ?? null,
                                'verein'       => trim($data[11] ?? ""),
                                'email'        => trim($data[12] ?? ""),
                                'mannschaft'   => trim($data[13] ?? ""),
                                'status'       => $data[14] ?? null,
                            ];

                            if (empty($mappedData['vorname']) || empty($mappedData['name']) || empty($mappedData['strecke'])) {
                                $errorCount++;
                                $errorMessages[] = "Zeile " . ($index + 1) . ": Name, Vorname oder Strecke fehlt.";
                                continue;
                            }

                            $EA_T = $this->initiateTeilnehmerFromCSV($mappedData, $konfiguration);
                            
                            // Persist directly to avoid flushing on every single row
                            $this->entityManager->persist($EA_T);
                            $successCount++;

                            // Batch flush for performance
                            if ($successCount % 100 === 0) {
                                $this->entityManager->flush();
                            }

                        } catch (\Throwable $e) {
                            $errorCount++;
                            $errorMessages[] = "Zeile " . ($index + 1) . " Fehler: " . $e->getMessage();
                        }
                    }
                    
                    // Final flush
                    $this->entityManager->flush();
                    
                    // Clear query/result cache if necessary
                    if (method_exists($this->entityManager->getConfiguration(), 'getResultCache') && $this->entityManager->getConfiguration()->getResultCache()) {
                        $this->entityManager->getConfiguration()->getResultCache()->clear();
                    }

                    if ($successCount > 0) {
                        $this->EA_Messages->addMessage("Erfolgreich $successCount Teilnehmer importiert.", 1001, EA_Message::MESSAGE_SUCCESS);
                    }
                    if ($errorCount > 0) {
                        $errorDetails = implode("<br>", array_slice($errorMessages, 0, 10));
                        if ($errorCount > 10) {
                            $errorDetails .= "<br>... und " . ($errorCount - 10) . " weitere Fehler.";
                        }
                        $this->EA_Messages->addMessage("$errorCount Fehler aufgetreten:<br>" . $errorDetails, 1002, EA_Message::MESSAGE_WARNING);
                    }

                } catch (\Exception $e) {
                    $this->EA_Messages->addMessage("Fehler beim Verarbeiten der CSV-Datei: " . $e->getMessage(), 193534574, EA_Message::MESSAGE_ERROR);
                }
            }
        }
        
        return $this->EA_FR->getFormImport();
    }

    private function initiateTeilnehmerFromCSV(array $data, \CharitySwimRun\classes\model\EA_Configuration $konfiguration): EA_Starter
    {
        $EA_T = new EA_Starter();
        $EA_T->setKonfiguration($konfiguration);

        if (!empty($data['startnummer'])) {
            $EA_T->setStartnummer(intval($data['startnummer']));
        }

        $transponder = ($konfiguration->getTransponder() === false) ? $EA_T->getStartnummer() : (!empty($data['transponder']) ? intval($data['transponder']) : null);
        $EA_T->setTransponder($transponder);

        $EA_T->setName($data['name']);
        $EA_T->setVorname($data['vorname']);

        $geburtsdatum = (is_numeric($data['geburtsdatum']) && strlen($data['geburtsdatum']) === 4) ? "01.01." . $data['geburtsdatum'] : $data['geburtsdatum'];
        try {
            $EA_T->setGeburtsdatum(new DateTimeImmutable($geburtsdatum));
        } catch (\Exception $e) {
            $EA_T->setGeburtsdatum(new DateTimeImmutable());
        }

        $altersklasse = $this->EA_AgeGroupRepository->findByGeburtsjahr($EA_T->getGeburtsdatum());
        $EA_T->setAltersklasse($altersklasse);

        $EA_T->setGeschlecht(strtoupper($data['geschlecht']));

        if (!empty($data['mannschaft'])) {
            $mannschaft = $this->EA_TeamRepository->loadById(intval($data['mannschaft']));
            if ($mannschaft) {
                $EA_T->setMannschaft($mannschaft);
            }
        }

        if (!empty($data['verein'])) {
            $verein = $this->EA_ClubRepository->loadByBezeichnung($data['verein']);
            $EA_T->setVerein($verein);
        }

        if (!empty($data['strecke'])) {
            if (ctype_digit(strval($data['strecke'])) && intval($data['strecke']) > 0) {
                $strecke = $this->EA_DistanceRepository->loadById(intval($data['strecke']));
            } else {
                $strecke = $this->EA_DistanceRepository->loadByBezeichnungLang($data['strecke']);
            }
            if ($strecke) {
                $EA_T->setStrecke($strecke);
            } else {
                throw new \Exception("Strecke '" . $data['strecke'] . "' nicht gefunden.");
            }
        }

        $EA_T->setStartgruppe(intval($data['startgruppe']));
        $EA_T->setMail(empty($data['email']) ? null : $data['email']);
        $EA_T->setPlz(empty($data['plz']) ? null : intval($data['plz']));
        $EA_T->setWohnort(empty($data['ort']) ? null : $data['ort']);
        $EA_T->setStrasse(empty($data['strasse']) ? null : $data['strasse']);

        $status = (!empty($data['status']) && intval($data['status']) >= 10) ? intval($data['status']) : 30;
        $EA_T->setStatus($status);

        $startzeit = ($konfiguration->getStarttyp() === "aba") ? new DateTimeImmutable() : null;
        $EA_T->setStartzeit($startzeit);

        return $EA_T;
    }
}