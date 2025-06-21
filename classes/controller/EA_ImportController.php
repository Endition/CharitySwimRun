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
            $ignorieren = isset($_POST['ignorieren']) ? "ja": "nein";
            $error = false;

            if (strtolower(substr($datei ['name'], -4)) !== ".csv") {
                $this->EA_Messages->addMessage("Datei muss eine .csv Datei sein.",193534574,EA_Message::MESSAGE_ERROR);            
                $error = true;
            } else {
                move_uploaded_file($datei ['tmp_name'], "einlesen.csv"); // ausgewählte Datei muss temporäre Hochgeladen werden
                // da der Direktzugriff verboten ist
                // ###########Daten in eine Array laden###########################
               $handle = fopen("einlesen.csv", "r");
                $i = 1;
                if ($handle !== false) {
                    $this->EA_Messages->addMessage("Datei erfolgreich geöffnet.",193534574,EA_Message::MESSAGE_SUCCESS);
                    // Kopfzeile überspringen, falls gewünscht
                    if ($ignorieren === "ja") {
                        fgetcsv($handle, 1000, $trennzeichen);
                    }
                    while (($data = fgetcsv($handle, 1000, $trennzeichen)) !== false) {
                            foreach ($data as &$field) {
                                #Datenbank is not UTF-8, deaktiviatet
                              #  $field = mb_convert_encoding($field, 'UTF-8', 'auto');
                            }
                        if ($data[2] !== "" && $data[3] !== "" && $data[4] !== "" && $data[5] !== "" && $data[6] !== "") {
                            $this->EA_Messages->addMessage("Unbedingt unter Teilnehmerübersicht die importierten Daten prüfen",235254753757,EA_Message::MESSAGE_INFO);
                            $EA_T = $this->initiateTeilnehmerFromCSV($messages, $data);
                            $answer = $this->EA_StarterRepository->create($EA_T);
                            if ($answer === true) {
                                $this->EA_Messages->addMessage("Zeile " . $i . " : Starter " . $data[1] . " " . $data[2]  . " erfolgreich angemeldet",1335375234,EA_Message::MESSAGE_SUCCESS);
                            } else {
                                $this->EA_Messages->addMessage("Zeile " . $i . " : Starternummer " . $data[1]  . " " . $data[2] . " " . $data[3] . " NICHT erfolgreich angemeldet",1235567775,EA_Message::MESSAGE_WARNING);
                            }
                        }
                        $this->EA_Messages->addMessage("Zeile überspringen. Nicht alle Werte ausgefüllt",456567346346345,EA_Message::MESSAGE_ERROR);
                        $i++;
                    }
                    fclose($handle);
                    unlink("einlesen.csv");
                } else {
                    $this->EA_Messages->addMessage("Datei NICHT erfolgreich geöffnet",193534574,EA_Message::MESSAGE_ERROR);
                    $error = true;
                }
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
         $EA_T->setKonfiguration($konfiguration);
         if(!empty($data[0])){
            $EA_T->setStartnummer(intval($data[0]));
         }
         $transponder = ($konfiguration->getTransponder() === false) ? $EA_T->getStartnummer() : intval($data[1]);
         $EA_T->setTransponder($transponder);
         $EA_T->setName(trim($data[3]) );
         $EA_T->setVorname(trim($data[2]) );
         $geburtsdatum = (is_numeric($data[4]) && strlen($data[4]) === 4) ? "01.01." . $data[4] : $data[4];
         $EA_T->setGeburtsdatum(new DateTimeImmutable($geburtsdatum));
        
         $altersklasse = $this->EA_AgeGroupRepository->findByGeburtsjahr($EA_T->getGeburtsdatum());
         $EA_T->setAltersklasse($altersklasse);
         
         $EA_T->setGeschlecht(strtoupper(trim($data[5])));
         if($data[13] !== ""){
            $mannschaft = $this->EA_TeamRepository->loadById($data[13]);
            $EA_T->setMannschaft($mannschaft);
         }
         if($data[11] !== ""){
         $verein = $this->EA_ClubRepository->loadByBezeichnung(mb_convert_encoding(trim($data[11]), 'UTF-8'));
         $EA_T->setVerein($verein);
         }

         if(ctype_digit($data[6]) && intval($data[6]) > 0){
            $strecke = $this->EA_DistanceRepository->loadById(intval($data[6]));
         } else {
            $strecke = $this->EA_DistanceRepository->loadByBezeichnungLang(mb_convert_encoding(trim($data[6]), 'UTF-8'));
         }
         $strecke = $this->EA_DistanceRepository->loadById(intval($data[6]));
         $EA_T->setStrecke($strecke);

         $EA_T->setStartgruppe(intval($data[7]));
         $EA_T->setMail(trim($data [12]));
         $EA_T->setPlz(intval($data[8]));
         $EA_T->setWohnort(mb_convert_encoding($data[9], 'UTF-8') );
         $EA_T->setStrasse(mb_convert_encoding($data[10], 'UTF-8') );
         $status = ($data[14] < 10) ? 10 : intval($data[13]);
         $EA_T->setStatus($status);
         $startzeit = ($konfiguration->getStarttyp() === "aba") ? new DateTimeImmutable() : null;
         $EA_T->setStartzeit($startzeit);
         return $EA_T;
     }

}