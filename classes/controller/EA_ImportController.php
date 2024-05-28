<?php
namespace CharitySwimRun\classes\controller;

use DateTimeImmutable;
use Doctrine\ORM\EntityManager;

use CharitySwimRun\classes\model\EA_AgeGroupRepository;

use CharitySwimRun\classes\model\EA_ConfigurationRepository;
use CharitySwimRun\classes\model\EA_Starter;
use CharitySwimRun\classes\model\EA_Message;

class EA_ImportController extends EA_Controller
{
    public function __construct( EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }

    
    public function getPageImport(): string
    {
        $messages = [];
        if (isset($_POST['sendImportData']) && $_FILES['datei'] !== null) {
            $datei = $_FILES['datei'];
            $trennzeichen = $_POST['trennzeichen'];
            $ignorieren = $_POST['ignorieren'];
            $error = false;

            if (strtolower(substr($datei ['name'], -4)) !== ".csv") {
                $this->EA_Messages->addMessage("Datei muss eine .csv Datei sein.",193534574,EA_Message::MESSAGE_ERROR);            
                $error = true;
            } else {
                move_uploaded_file($datei ['tmp_name'], "einlesen.csv"); // ausgewählte Datei muss temporäre Hochgeladen werden
                // da der Direktzugriff verboten ist
                $datei = file("einlesen.csv");
                // ###########Daten in eine Array laden###########################
                $handle = fopen("einlesen.csv", "r"); // Datei zum Lesen �ffnen
                $zeile = 1; // zeile setzen, damit nicht alles eingelesen wird
                $einlesenbeginn = ($ignorieren === "ja") ? 2 : 1;


                if ($handle != NULL) {
                    $this->EA_Messages->addMessage("Datei erfolgreich geöffnet.",193534574,EA_Message::MESSAGE_SUCCESS);            
                } else {
                    $this->EA_Messages->addMessage("Datei NICHT erfolgreich ge&ouml;ffnet",193534574,EA_Message::MESSAGE_ERROR);            
                    $error = true;
                }
                $i = 1;
                while (!feof($handle)) {
                    if ($zeile >= $einlesenbeginn) {
                        $this->EA_Messages->addMessage("Unbedingt unter Teilnehmerübersicht die importierten Daten prüfen",235254753757,EA_Message::MESSAGE_INFO);            
                        while (($data = fgetcsv($handle, 1000, $trennzeichen)) !== false) { // Daten werden aus der Datei
                            if ($data [0] != "" and $data [1] != "" and $data [2] != "") {
                                #echo "<pre>";
                                #print_r($data);
                                #echo "</pre>";
                                $EA_T = $this->initiateTeilnehmerFromCSV($messages, $data);
                                $answer = $this->EA_StarterRepository->create($EA_T);
                                if ($answer === true) {
                                    $this->EA_Messages->addMessage("Zeile " . $i . " : Starter " . mb_convert_encoding($data[1], 'UTF-8') . " " . mb_convert_encoding($data[2], 'UTF-8')  . " erfolgreich angemeldet",1335375234,EA_Message::MESSAGE_SUCCESS);            
                                } else {
                                    $this->EA_Messages->addMessage("Zeile " . $i . " : Starternummer " . mb_convert_encoding($data[1], 'UTF-8')  . " " . mb_convert_encoding($data[2], 'UTF-8') . " " . mb_convert_encoding($data[3], 'UTF-8')  . " NICHT erfolgreich angemeldet",1235567775,EA_Message::MESSAGE_WARNING);            
                                }
                            }
                            $i++;
                        }
                    } else {
                        $ab_in_den_muell = fgets($handle, 4096);
                    }
                    $zeile++;
                }
                fclose($handle); // Datei schließen
                unlink("einlesen.csv"); // löscht die Datei wieder
            }
        }
        $content = $this->EA_FR->getFormImport();
        return $content;
    }
    
        /*
     * Import from CSV, Vereine Strecken AKS übergeben, damit sie nicht jedes mal geladen werden müssen
     */

     private function initiateTeilnehmerFromCSV(&$messages, $data): EA_Starter
     {
         $konfiguration = $this->EA_ConfigurationRepository->load();
         $EA_T = null;
         $EA_T = new EA_Starter();
         $EA_T->setStartnummer(trim($data[0]));
         $transponder = ($konfiguration->getTransponder() === false) ? $EA_T->getStartnummer() : trim($data[1]);
         $EA_T->setTransponder($transponder);
         $EA_T->setName(mb_convert_encoding(trim($data[1]), 'UTF-8') );
         $EA_T->setVorname(mb_convert_encoding(trim($data[2]), 'UTF-8') );
         $geburtsdatum = (is_numeric($data[4]) && strlen($data[4]) == 4) ? "01.01." . $data[4] : $data[4];
         $EA_T->setGeburtsdatum(new DateTimeImmutable($geburtsdatum));
        
         $altersklasse = $this->EA_AgeGroupRepository->findByGeburtsjahr($EA_T->getGeburtsdatum());
         $EA_T->setAltersklasse($altersklasse);
         
         $EA_T->setGeschlecht(strtoupper(trim($data[5])));
         
         $mannschaft = $this->EA_TeamRepository->loadById($data[13]);
         $EA_T->setMannschaft($mannschaft);
        
         $verein = $this->EA_ClubRepository->loadByBezeichnung(mb_convert_encoding(trim($data[11]), 'UTF-8'));
         $EA_T->setVerein($verein);

         $strecke = $this->EA_DistanceRepository->loadByBezeichnungLang(trim($data[6]));
         $EA_T->setStrecke($strecke);

         $EA_T->setStartgruppe(trim($data[7]));
         $EA_T->setMail(trim($data [12]));
         $EA_T->setPlz(trim($data[8]));
         $EA_T->setWohnort(mb_convert_encoding($data[9], 'UTF-8') );
         $EA_T->setStrasse(mb_convert_encoding($data[10], 'UTF-8') );
         $status = ($data[14] < 10) ? 10 : $data [13];
         $EA_T->setStatus($status);
         $startzeit = ($konfiguration->getStarttyp() === "aba") ? new DateTimeImmutable() : null;
         $EA_T->setStartzeit($startzeit);
         return $EA_T;
     }

}