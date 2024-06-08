<?php
namespace CharitySwimRun\classes\controller;

use CharitySwimRun\classes\model\EA_Repository;
use CharitySwimRun\classes\model\EA_User;
use CharitySwimRun\classes\model\EA_Hit;
use CharitySwimRun\classes\controller\EA_SimulatorController;

class EA_ApiController extends EA_Controller
{

    public function __construct(EA_Repository $EA_Repository) 
    {
        parent::__construct($EA_Repository->getEntityManager());
        //update Cache on every call
        $this->EA_HitRepository->updateImpulseCache();
    }

    public function handleRequest(string $requestMethod, string $route, array $paramList) : string
    {
        //set standardvalue
        $responseList = $this->notFoundresponseList();

        if (isset($_SESSION['loggedin']) && $_SESSION['userroleId'] <= EA_User::USERROLE_ANMELDUNG) {
            $responseList = $this->handleRequestAdministrativ($requestMethod,$route,$paramList);
        }else{
            $responseList =  $this->handleRequestPublic($requestMethod,$route,$paramList);
        }

        header($responseList['status_code_header']);
        if ($responseList['body']) {
            return $responseList['body'];
        }
        return "";
    }


    private function handleRequestPublic(string $requestMethod, string $route, array $paramList) : array
    {
        $responseList = [];
        switch ($requestMethod) {
            case 'GET':
                $responseList = $this->handleGetRequestPublic($route,$paramList);
                break;
            default:
                $responseList = $this->notFoundresponseList();
                break;
        }
        return $responseList;
    }

    private function handleRequestAdministrativ(string $requestMethod, string $route, array $paramList) : array
    {
        $responseList = [];

        switch ($requestMethod) {
            case 'GET':
                $responseList = $this->handleGetRequestAdministrativ($route,$paramList);
                break;
            case 'POST':
                $responseList = $this->handlePostRequestAdministrativ($route,$paramList);
                break;
            case 'PUT':
                // not implemented
                //$responseList = $this->handlePutRequest($route,$paramList);
                break;
            case 'DELETE':
                // not implemented
                //$responseList = $this->handleDeleteRequest($route,$paramList);
                break;
            default:
                $responseList = $this->notFoundresponseList();
                break;
        }
        return $responseList;
    }

    private function handleGetRequestPublic(string $route, array $paramList): array
    {
        $responseList = [];
        switch ($route) {
            case 'teilnehmer':
                $EA_StarterController = new EA_StarterController($this->entityManager);
                $responseList = $this->handleGetTeilnehmerPublic($paramList);
                break;
            default:
                $responseList = $this->notFoundresponseList();
                break;
        }
        return $responseList;
    }

    private function handleGetRequestAdministrativ(string $route, array $paramList): array
    {
        $responseList = [];
        switch ($route) {
            case 'teilnehmer':
                $EA_StarterController = new EA_StarterController($this->entityManager);
                $responseList = $this->handleGetTeilnehmerAdministrativ($paramList);
                break;
            case 'verein':
                $EA_ClubController = new EA_ClubController($this->entityManager);
                $responseList = $this->handleGetVereinAdministrativ($paramList);
                break;
            default:
                $responseList = $this->notFoundresponseList();
                break;
        }
        return $responseList;
    }

    private function handlePostRequestAdministrativ(string $route, array $paramList): array
    {
        $responseList = [];
        switch ($route) {
            case 'urkunden':
                $EA_CertificateController = new EA_CertificateController($this->entityManager);
                $responseList = $this->handlePostUrkundenAdministrativ($paramList);
                break;
            case 'status':
                $responseList = $this->handlePostStatusAdministrativ($paramList);
                break;
            case 'impulse':
                 $responseList = $this->handlePostImpulseAdministrativ($paramList);
                break;
            default:
                $responseList = $this->notFoundresponseList();
                break;
        }
        return $responseList;
    }

    private function handlePostImpulseAdministrativ(array $paramList): array
    {
        $responseList = [];

        if($paramList[0] === "create"){
            if (isset($_POST['id'])) {
                $teilnehmerId = filter_input(INPUT_POST,"id",FILTER_SANITIZE_NUMBER_INT);
            } else {
                return $this->unprocessableEntityresponseList();
            }

            $EA_T = $this->EA_StarterRepository->loadById($teilnehmerId);
            $EA_I = new EA_Hit();
            $EA_I->setTeilnehmer($EA_T);
            date_default_timezone_set("Europe/Berlin");
            $EA_I->setTimestamp(time());
            $EA_I->setTransponderId($EA_T->getTransponder());
            $EA_I->setLeser(99);
            $this->EA_HitRepository->create($EA_I);
        }

        $responseList['status_code_header'] = 'HTTP/1.1 200 OK';
        $responseList['body'] = json_encode([]);
        return $responseList;
    }

    private function handlePostUrkundenAdministrativ(array $paramList): array
    {
        $responseList = [];

        if($paramList[0] === "update"){
            $EA_U = $this->EA_CertificateElementRepository->loadById(filter_input(INPUT_POST,"id",FILTER_SANITIZE_NUMBER_INT));

            $x_wert = (isset($_POST['x_wert'])) ? htmlspecialchars($_POST['x_wert']) : $EA_U->getX_wert();
            $y_wert = (isset($_POST['y_wert'])) ? htmlspecialchars($_POST['y_wert']) : $EA_U->getY_wert();
            $breite = (isset($_POST['breite'])) ? htmlspecialchars($_POST['breite']) : $EA_U->getBreite();
            $hoehe = (isset($_POST['hoehe'])) ? htmlspecialchars($_POST['hoehe']) : $EA_U->getHoehe();
        
            $EA_U->setX_wert($x_wert);
            $EA_U->setY_wert($y_wert);
            $EA_U->setBreite($breite);
            $EA_U->setHoehe($hoehe);

            $this->EA_CertificateElementRepository->update();
        }

        $responseList['status_code_header'] = 'HTTP/1.1 200 OK';
        $responseList['body'] = json_encode([]);
        return $responseList;
    }

    private function handlePostStatusAdministrativ(array $paramList): array
    {
        $responseList = [];

        if($paramList[0] === "update"){
            if (isset($_POST['status']) && $_POST['status'] > 0 && isset($_POST['id']) && $_POST['id'] > 0) {
                $id = (int)filter_input(INPUT_POST,"id",FILTER_SANITIZE_NUMBER_INT);
                $status = (int)filter_input(INPUT_POST,"status",FILTER_SANITIZE_NUMBER_INT);
            } else {
                return $this->unprocessableEntityresponseList();
            }
            $EA_T = $this->EA_StarterRepository->loadById($id);
            $EA_T->setStatus($status);
            $this->EA_StarterRepository->update();
        }

        $responseList['status_code_header'] = 'HTTP/1.1 200 OK';
        $responseList['body'] = json_encode([]);
        return $responseList;
    }

    private function handleGetVereinAdministrativ(array $paramList): array
    {
        $responseList = [];
        $result = [];
        
        if($paramList[0] === "search"){
            $searchName = $paramList[0] === "search" ? htmlspecialchars($paramList[1]) : null;

            $vereinList = $this->EA_ClubRepository->loadList("verein",$searchName);            
            if ($vereinList === null) {
                return $this->notFoundresponseList();
            }
            foreach($vereinList as $verein){
                $result[] = [   
                    "id"=>$verein->getId(),
                    "startnummer"=>$verein->getVerein(),
                    //JqueryUi needs exact these parameters
                    "label" => $verein->getVerein(),
                    "value" => $verein->getId()
                ];
            }
        }


        $responseList['status_code_header'] = 'HTTP/1.1 200 OK';
        $responseList['body'] = json_encode($result);
        return $responseList;
    }

    private function handleGetTeilnehmerAdministrativ(array $paramList): array
    {
        $responseList = [];
        $result = [];
        if($paramList[0] === "simulator"){
            $EA_SimulatorController = new EA_SimulatorController($this->entityManager);
            $result = $EA_SimulatorController->createRandomTeilnehmer();
        }
        if($paramList[0] === "startnummer"){
            $startnummer = $paramList[0] === "startnummer" ? (int)$paramList[1] : null;
            $teilnehmer = $this->EA_StarterRepository->loadByFilter(null,$startnummer);            
            if ($teilnehmer === null) {
                return $this->notFoundresponseList();
            }

            $result = [   
                "id"=>$teilnehmer->getId(),
                "startnummer"=>$teilnehmer->getStartnummer(),
                "vorname"=>$teilnehmer->getVorname(),
                "name"=>$teilnehmer->getName(),
                "meter"=>$teilnehmer->getMeter(),
                "streckenart"=>$teilnehmer->getStreckenart(),
                "wertung"=>$teilnehmer->getWertung("lang"),
                "naechsteWertung"=>$teilnehmer->getNaechsteWertung(),
                //JqueryUi needs these parameters
                "label" => "StNr: ". $teilnehmer->getStartnummer()." - ".$teilnehmer->getGesamtname(),
                "value" => $teilnehmer->getId()
            ];
        }
        if($paramList[0] === "strecke"){
            $strecke = $this->EA_DistanceRepository->loadById((int)$paramList[1]);
            $teilnehmerList = $this->EA_StarterRepository->loadList($strecke,null,null,null,null,null,null,"startnummer","ASC");            
            if ($teilnehmerList === []) {
                return $this->notFoundresponseList();
            }
            foreach($teilnehmerList as $teilnehmer){
                $result[] = [   
                    "id"=>$teilnehmer->getId(),
                    "startnummer"=>$teilnehmer->getStartnummer(),
                    "vorname"=>$teilnehmer->getVorname(),
                    "name"=>$teilnehmer->getName(),
                    "meter"=>$teilnehmer->getMeter(),
                ];
            }

        }

        if($paramList[0] === "search"){
            
            if(is_numeric($paramList[1])){
                $searchStartnummer = $paramList[0] === "search" ? htmlspecialchars($paramList[1]) : null;
                $searchName  = null;
                $searchVorname = null;
            }else{
                $searchName = $paramList[0] === "search" ? htmlspecialchars($paramList[1]) : null;
                $searchVorname = $paramList[0] === "search" ? htmlspecialchars($paramList[1]) : null;
                $searchStartnummer = null;
            }

            $teilnehmerList = $this->EA_StarterRepository->loadList(null,null,null,null,null,null,null,"name","ASC",null,null,null,null,null,$searchStartnummer,$searchName,$searchVorname);            
            if ($teilnehmerList === null) {
                return $this->notFoundresponseList();
            }
            foreach($teilnehmerList as $teilnehmer){
                $result[] = [   
                    "id"=>$teilnehmer->getId(),
                    "startnummer"=>$teilnehmer->getStartnummer(),
                    "vorname"=>$teilnehmer->getVorname(),
                    "name"=>$teilnehmer->getName(),
                    "meter"=>$teilnehmer->getMeter(),
                    "streckenart"=>$teilnehmer->getStreckenart(),
                    "wertung"=>$teilnehmer->getWertung("lang"),
                    "naechsteWertung"=>$teilnehmer->getNaechsteWertung(),
                    //JqueryUi needs exact these parameters
                    "label" => "StNr: ". $teilnehmer->getStartnummer()." | ".$teilnehmer->getGesamtname()." | Wertung: ".$teilnehmer->getWertung()." | Meter: ".$teilnehmer->getMeter(),
                    "value" => $teilnehmer->getId()
                ];
            }
        }
        if($paramList[0] === "livebuchungen"){
            $biggerAsTimestamp = (isset($paramList[1]) && $paramList[1] !== "") ? (int)$paramList[1] : null;
            $impulseList = $this->EA_HitRepository->loadList("i.timestamp","DESC",20,"i.timestamp", $biggerAsTimestamp );
            foreach($impulseList as $impuls){
                    //catch error hits
                    if($impuls->getTeilnehmer() === null){
                        continue;
                    }
                    //costs 200% performance, calculates the time per round
                    $impuls->getTeilnehmer()->getImpulseListGueltige(true);
                    $result[] = [
                        "id"=>$impuls->getTeilnehmer()->getId(),
                        "gesamtname"=>$impuls->getTeilnehmer()->getGesamtname(),
                        "startnummer"=>$impuls->getTeilnehmer()->getStartnummer(),
                        "meter"=>$impuls->getTeilnehmer()->getMeter(),
                        "streckenart"=>$impuls->getTeilnehmer()->getStreckenart(),
                        "timestamp" => $impuls->getTimestamp(),
                        "zeit" => date("H:i:s", $impuls->getTimestamp()),
                        "rundezeit" => $impuls->getRundenzeit("H:i:s"),
                        "gesamtzeit" => $impuls->getGesamtzeit("H:i:s"),
                    ];
            }   
        }

        $responseList['status_code_header'] = 'HTTP/1.1 200 OK';
        $responseList['body'] = json_encode($result);
        return $responseList;
    }

    private function handleGetTeilnehmerPublic(array $paramList): array
    {
        $responseList = [];
        $result = [];

        if($paramList[0] === "startnummer"){
            $teilnehmer = $this->EA_StarterRepository->loadByFilter(null, $paramList[1]);
            
            if ($teilnehmer === null) {
                return $this->notFoundresponseList();
            }

            $result = [
                "id"=>$teilnehmer->getId(),
                "startnummer"=>$teilnehmer->getStartnummer(),
                "meter"=>$teilnehmer->getMeter(),
                "streckenart"=>$teilnehmer->getStreckenart(),
                "wertung"=>$teilnehmer->getWertung("lang"),
                "naechsteWertung"=>$teilnehmer->getNaechsteWertung(),
            ];
        }
        if($paramList[0] === "livebuchungen"){
            $biggerAsTimestamp = (isset($paramList[1]) && $paramList[1] !== "") ? (int)$paramList[1] : null;
            $impulseList = $this->EA_HitRepository->loadList("i.timestamp","DESC",20,"i.timestamp", $biggerAsTimestamp );
            foreach($impulseList as $impuls){
                    //costs 200% performance, calculates the time per round
                    $impuls->getTeilnehmer()->getImpulseListGueltige(true);
                    $result[] = [
                        "id"=>$impuls->getTeilnehmer()->getId(),
                        "gesamtname"=>$impuls->getTeilnehmer()->getGesamtname(),
                        "startnummer"=>$impuls->getTeilnehmer()->getStartnummer(),
                        "meter"=>$impuls->getTeilnehmer()->getMeter(),
                        "streckenart"=>$impuls->getTeilnehmer()->getStreckenart(),
                        "timestamp" => $impuls->getTimestamp(),
                        "zeit" => date("H:i:s", $impuls->getTimestamp()),
                        "rundezeit" => $impuls->getRundenzeit("H:i:s"),
                        "gesamtzeit" => $impuls->getGesamtzeit("H:i:s"),
                    ];
            }   
        }
        $responseList['status_code_header'] = 'HTTP/1.1 200 OK';
        $responseList['body'] = json_encode($result);
        return $responseList;
    }

    private function unprocessableEntityresponseList(): array
    {
        $responseList['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $responseList['body'] = json_encode(['message' => 'Invalid input']);
        return $responseList;
    }

    private function notFoundresponseList(): array
    {
        $responseList['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $responseList['body'] = json_encode(['message' => 'Resource Not Found']);
        return $responseList;
    }

}