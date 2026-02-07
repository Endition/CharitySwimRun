<?php
//has to be the very first thing
session_start();
use \CharitySwimRun\classes\controller\EA_PDFController;
use CharitySwimRun\classes\model\EA_User;
use \CharitySwimRun\classes\model\EA_Repository;


require_once 'config/config.php';
require_once CORE_PATH . 'EA_Autoloader.php';
$path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'config', 'dbConfigDaten.php']);
if (file_exists($path)) {
    include_once($path);
} 

if(!isset($EA_SQL) || $EA_SQL === []){
    $EA_SQL = ["datenbank"=>"information_schema", "benutzer"=>"","passwort"=>"","server"=>""];
}

if (file_exists('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
}else{
    echo '<html><head></head><body><p>Bitte erst die abhängingen Tools per composer installieren</p></body></html>';
    return;
}

//Allow Ajax only when Session is active and role fits
if (!isset($_SESSION['loggedin']) || EA_User::USERROLE_ANMELDUNG < $_SESSION['userroleId']) {
    exit; 
}

$EA_Repository = new EA_Repository($EA_SQL["benutzer"],$EA_SQL["passwort"],$EA_SQL["server"]);

$EA_AC = new EA_PDFController($EA_Repository);
echo $EA_AC->getPDFAdmin();
