<?php
namespace CharitySwimRun\classes\controller;

use CharitySwimRun\classes\core\EA_MenueRenderer;
use CharitySwimRun\classes\model\EA_Repository;
use CharitySwimRun\classes\model\EA_User;
use CharitySwimRun\classes\model\EA_Message;

class EA_AdminController extends EA_Controller
{

    private EA_Repository $EA_Repository;
    public function __construct(EA_Repository $EA_Repository)
    {
        parent::__construct($EA_Repository->getEntityManager());
        $this->EA_Repository = $EA_Repository;
    }


    public function getAdmin(): string
    {
        $content = "";
        $innercontent = "";

            //Alle SmartyTemplates löschen
            $this->EA_R->deleteSmartyCache();
            $_GET['doc'] = (isset($_GET['doc'])) ? $_GET['doc'] : null;
            if(!$this->isAuthenticated()){
                return "keine Rechte";
            }

            //if there is no configuration show configuration Page
            if($this->konfiguration === null){
                $_GET['doc'] = "konfiguration";  
            }

            //update ImpulseCache on every Call
            $this->EA_ImpulsRepository->updateImpulseCache();
            
            switch ($_GET['doc']) {
                case "dashboard":
                    $dashboardController = new EA_DashboardController($this->entityManager);
                    $innercontent .= $dashboardController->getPageDashboard();
                    break;
                case "db":
                    $datenbankController = new EA_DatenbankController( $this->EA_Repository);
                    $innercontent .= $datenbankController->getPageDb();
                    break;
                case "users":
                    $userController = new EA_UserController( $this->entityManager);
                    $innercontent .= $userController->getPageUser();
                    break;
                case "konfiguration":
                    $konfigurationController = new EA_KonfigurationController( $this->entityManager);
                    $innercontent .= $konfigurationController->getPageKonfiguration();
                    break;
                case "altersklassen":
                    $altersklassenController = new EA_AltersklassenController( $this->entityManager);
                    $innercontent .= $altersklassenController->getPageAltersklassen();
                    break;
                case "strecken":
                    $streckenController = new EA_DistanceController($this->entityManager);
                    $innercontent .= $streckenController->getPageStrecken();
                    break;
                case "specialevaluation":
                    $specialEvaluationController = new EA_SpecialEvaluationController($this->entityManager);
                    $innercontent .= $specialEvaluationController->getPageSpecialEvaluationn();
                    break;
                case "mannschaftskategorie":
                    $mannschaftskategorieController = new EA_TeamCategoryController( $this->entityManager);
                    $innercontent .= $mannschaftskategorieController->getPageMannschaftskategorie();
                    break;
                case "urkundengenerator":
                    $urkundengeneratorController = new EA_UrkundengeneratorController( $this->entityManager);
                    $innercontent .= $urkundengeneratorController->getPageUrkundengenerator();
                    break;
                case "import":
                    $importController = new EA_ImportController( $this->entityManager);
                    $innercontent .= $importController->getPageImport();
                    break;
                case "buchungsuebersicht":
                    $buchungsuebersichtController = new EA_BuchungsuebersichtController( $this->entityManager);
                    $innercontent .= $buchungsuebersichtController->getPageBuchungsuebersicht();
                    break;
                case "teilnehmeruebersicht":
                    $teilnehmeruebersichtController = new EA_TeilnehmeruebersichtController( $this->entityManager);
                    $innercontent .= $teilnehmeruebersichtController->getPageTeilnehmeruebersicht();
                    break;
                case "teilnehmer":
                    $teilnehmerController = new EA_TeilnehmerController($this->entityManager);
                    $innercontent .= $teilnehmerController->getPageTeilnehmer();
                    break;
                case "mannschaften":
                    $mannschaftenController = new EA_MannschaftenController( $this->entityManager);
                    $innercontent .= $mannschaftenController->getPageMannschaften();
                    break;
                case "vereine":
                    $vereineController = new EA_ClubController( $this->entityManager);
                    $innercontent .= $vereineController->getPageVereine();
                    break;
                case "startzeiten":
                    $startzeitenController = new EA_StartzeitenController( $this->entityManager);
                    $innercontent .= $startzeitenController->getPageStartzeiten();
                    break;
                case "buchungenstarter":
                    $buchungenEinesStartesControllers = new EA_BuchungenEinesTeilnehmersController($this->EA_Repository);
                    $innercontent .= $buchungenEinesStartesControllers->getPageBuchungenStarter();
                    break;
                case "manuelleeingabe":
                    $manuelleEingabeController = new EA_ManuelleEingabeController( $this->entityManager);
                    $innercontent .= $manuelleEingabeController->getPageManuelleEingabe();
                    break;
                case "statusverwalten":
                    $statusVerwaltenController = new EA_StatusVerwaltenController( $this->entityManager);
                    $innercontent .= $statusVerwaltenController->getPageStatusVerwalten();
                    break;
                case "transponderrueckgabe":
                    $transponderrueckgabeController = new EA_TransponderRueckgabeController( $this->entityManager);
                    $innercontent .= $transponderrueckgabeController->getPageTransponderrueckgabe();
                    break;
                case "fehlbuchungen":
                    $fehlbuchungenController = new EA_FehlbuchungenController( $this->entityManager);
                    $innercontent .= $fehlbuchungenController->getPageFehlbuchungen();
                    break;
                case "spezialabfragen":
                    $spezialabfragenController = new EA_SpezialabfragenController( $this->entityManager);
                    $innercontent .= $spezialabfragenController->getPageSpezialabfragen();
                    break;
                case "ergebnisse":
                    $ergebnisController = new EA_ErgebnisController( $this->entityManager);
                    $innercontent .= $ergebnisController->getPageErgebnisse();
                    break;
                case "urkunden":
                    $urkundeController = new EA_UrkundeController( $this->entityManager);
                    $innercontent .= $urkundeController->getPageUrkunden();
                    break;
                case "meldelisten":
                    $meldelistenController = new EA_MeldelistenController( $this->entityManager);
                    $innercontent .= $meldelistenController->getPageMeldelisten();
                    break;
                case "statistik":
                    $statistikController = new EA_StatistikController( $this->EA_Repository);
                    $innercontent .= $statistikController->getPageStatistik();
                    break;
                case "selbstanmeldung":
                    $selbstanmeldungController = new EA_SelbstanmeldungController( $this->entityManager);
                    $innercontent .= $selbstanmeldungController->getPageSelbstanmeldung();
                    break;
                case "kurzauskunft":
                    $innercontent .= $this->EA_FR->getFormKurzauskunft();
                    break;
                case "logout":
                    $innercontent .= $this->getPageLogout();
                    break;
                case "login":
                    $innercontent .= $this->getPageLogin();
                    break;
                default:
                    $innercontent .= $this->EA_R->renderIndex($this->getServerNetworkIP());
                    break;
            }
        
        


        //render messages and content
        $content .= $this->EA_Messages->renderMessageAlertList();
        $content .= $innercontent;
        return $content;
    }
    

    private function isAuthenticated(): bool
    {
        //load menue to get the role - site relation
        $EA_Menue = new EA_MenueRenderer();
        $menue = $EA_Menue->getMenueStructure();
        foreach ($menue as $key => $menueElement) {
            //if requested site = array element AND necassary role BIGGER than UserRole ID -> pass; If it is smaller -> block access
            if($menueElement['doc'] === $_GET['doc'] && ($menueElement['necassaryRoleId'] === EA_User::USERROLE_PUBLIC  || $menueElement['necassaryRoleId'] >= $_SESSION['userroleId'] )){
                return true;
            }
            //Check Submenuentries
            if(isset($menueElement['subMenue'])){
                foreach ($menueElement['subMenue'] as $subKey => $subMenueElement) {
                    if($subMenueElement['doc'] === $_GET['doc'] && ( $subMenueElement['necassaryRoleId'] === EA_User::USERROLE_PUBLIC || $subMenueElement['necassaryRoleId'] >= $_SESSION['userroleId'] )){
                        return true;
                    }
                }
            }
        }
        return false;
    }

    private function getPageLogin(): string
    {
        $EA_LoginController = new EA_LoginController($this->entityManager);
        if($EA_LoginController->checkIfAdminExist() === true){
            return $EA_LoginController->getLogin();
        }else{
            $this->EA_Messages->addMessage("Solange kein Adminaccount angelegt ist, ist kein Login notwendig",1877556777,EA_Message::MESSAGE_INFO);            
            $userController = new EA_UserController( $this->entityManager);
            return $userController->getPageUser();
        }
    }

    private function getPageLogout(): string
    {
        session_destroy();
        $this->EA_Messages->addMessage("Ausgeloggt",145678377,EA_Message::MESSAGE_INFO);      
        return "";      
    }

    private function getServerNetworkIP(): array
    {
        $meldungen = [];
    	$IP_Config = shell_exec("ipconfig");
        $pfadarray = explode("/", getenv('SCRIPT_FILENAME')); // Splittet den PfadNamen anhand / auf und zerteilt ihn
        // echo $IP_Config; //Gibt ipconfig komplett aus
        $matches = [];
        preg_match_all('([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})', $IP_Config, $matches); // Ps rausfiltern
        foreach ($matches [0] as $ip) { // �?ber alle Werte laufen, matches[0] ergibts sich aus der speicherung der werte in preg_match_all
            $aufgesplittet = explode(".", $ip); // Ips aufsplitten
            if ($aufgesplittet [0] != "255" and $aufgesplittet [1] != "255") { // Subnetzmasken Filtern
                $meldungen [] = $ip . "/" . $pfadarray [count($pfadarray) - 2] . "/";
            }
        }
        // print_r($matches[0]); //Gibt das Ergebnisarray mit allen IPs aus
        return $meldungen;
    }
}
