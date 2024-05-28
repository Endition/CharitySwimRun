<?php
//has to be the very first thing
session_start();
header("Content-Type: application/json");

use CharitySwimRun\classes\model\EA_User;
use CharitySwimRun\classes\model\EA_Repository;
use CharitySwimRun\classes\controller\EA_ApiController;

// error_reporting(E_ALL);
// ini_set('display_errors', true);
require_once '../config/config.php';
include_once("../config/dbConfigDaten.php");
include_once('../classes/core/EA_Autoloader.php');
require_once "../vendor/autoload.php";

//Credentials are checked inside API Controller

$EA_Repository = new EA_Repository($EA_SQL["benutzer"],$EA_SQL["passwort"],$EA_SQL["server"]);


$requestMethod = $_SERVER["REQUEST_METHOD"];
$requestUri = explode("/", trim($_SERVER["REQUEST_URI"], "/"));

// CharitySwimRun/Api/...
$numberInUriApi = 1;
$numberInUriRessource = 2;
$numberInUriParamStart = 3;

if ($requestUri[$numberInUriApi] !== 'api' || count($requestUri) < $numberInUriRessource) {
    http_response_code(404);
    echo json_encode(["message" => "Not Found"]);
    exit();
}

$resource = $requestUri[$numberInUriRessource];

$EA_ApiController = new EA_ApiController($EA_Repository);
echo $EA_ApiController->handleRequest($requestMethod,$resource,array_slice($requestUri, $numberInUriParamStart));