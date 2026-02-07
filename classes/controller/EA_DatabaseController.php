<?php
namespace CharitySwimRun\classes\controller;

use Doctrine\ORM\EntityManager;
use CharitySwimRun\classes\model\EA_Message;
use CharitySwimRun\classes\model\EA_Certificate;
use CharitySwimRun\classes\renderer\EA_FormRenderer;
use CharitySwimRun\classes\model\EA_Repository;
use CharitySwimRun\classes\model\EA_Messages;
use CharitySwimRun\classes\model\EA_RfidChip;
use CharitySwimRun\classes\model\EA_FemaleFirstname;

//Because this controller creates the DB connection, no heritage
class EA_DatabaseController
{
    protected EA_FormRenderer $EA_FR;
    private EA_Repository $EA_Repository;
    protected EA_Messages $EA_Messages;

    public function __construct(?EA_Repository $EA_Repository)
    {
        $this->EA_Repository = $EA_Repository === null ? new EA_Repository("","","", true) : $EA_Repository;
        $this->EA_FR = new EA_FormRenderer();
        $this->EA_Messages = new EA_Messages();
    }

    public function getPageDb(): string
    {
        $content = "";
        if (isset($_POST['sendDatabaseData'])) {
            $this->saveDatabaseData();
        }

        if (isset($_POST['resetEvent']) && $_GET['action'] === "resetEvent") {
            $this->resetEvent();
        }

        if (isset($_POST['resetDatabase']) && $_GET['action'] === "deleteDatabase") {
            $this->resetDatabase();
        }

        if (isset($_POST['dropDatabase']) && $_GET['action'] === "dropDatabase") {
            $this->dropDatabase();
        }

        $content .= $this->EA_FR->getFormDatabaseData($this->EA_Repository);
       
        if($this->EA_Repository->isDoctrineConnected()){
            $test = $this->EA_Repository->getDatabaseTableList();
            $content .= $this->EA_FR->getTablesInDatabase($this->EA_Repository->getDatabaseTableList());
        }
        $content .= $this->EA_Messages->renderMessageAlertList();
        return $content;
    }

    private function resetEvent(): void
    {
        $this->EA_Repository->resetDatabase("RESETEVENT");  
        $this->EA_Messages->addMessage("Event zurückgesetzt",123547823777,EA_Message::MESSAGE_SUCCESS);            
    }

    private function resetDatabase(): void
    {
        $this->EA_Repository->resetDatabase("TRUNCATE");  
        $this->EA_Messages->addMessage("Datenbank geleert",413546576546,EA_Message::MESSAGE_SUCCESS);            
    }

    private function dropDatabase(): void
    {
        $this->EA_Repository->resetDatabase("DROP");  
        $this->EA_Repository->createDatabaseTables();
        $this->EA_Messages->addMessage("Datenbank neu erstellt",537567354477,EA_Message::MESSAGE_SUCCESS);            
    }

    private function saveDatabaseData()
    {
        $this->EA_Repository->setUser(htmlspecialchars($_POST['benutzer']));
        $this->EA_Repository->setPassword(htmlspecialchars($_POST['passwort']));
        $this->EA_Repository->setServer(htmlspecialchars($_POST['server']));
        $this->EA_Repository->connectDoctrin();
        $this->EA_Repository->createDatabase();

        if ($this->EA_Repository->isDoctrineConnected() === true) {
            $path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'config', 'dbConfigDaten.php']);
	    $this->writeDbConfigDatenFile($path);
            $this->EA_Repository->createDatabaseTables();
            $this->createUrkundeStandardentries($this->EA_Repository->getEntityManager());
            $this->createTransponderStandardentries($this->EA_Repository->getEntityManager());
            $this->createFemaleFirstnamesStandardentries($this->EA_Repository->getEntityManager());
        }else{
            $this->EA_Messages->addMessage("Konnte keine Verbindung herstellen",123457777,EA_Message::MESSAGE_ERROR);            
        }
    }

    private function createUrkundeStandardentries(EntityManager $entityManager): void
    {
        foreach(EA_Certificate::getStandardElemente() as $urkundenelement){
            $entityManager->persist($urkundenelement);
        }
        $entityManager->flush();

    }

    private function createTransponderStandardentries(EntityManager $entityManager): void
    {
        foreach(EA_RfidChip::getStandardElemente() as $transponder){
            $entityManager->persist($transponder);
        }
        $entityManager->flush();
    }

    private function createFemaleFirstnamesStandardentries(EntityManager $entityManager): void
    {
        foreach(EA_FemaleFirstname::getStandardElemente() as $firstname){
            $entityManager->persist($firstname);
        }
        $entityManager->flush();
    }

    private function writeDbConfigDatenFile(string $path): ?bool
    {
        $text = "<?php   \$EA_SQL = [];
                    \$EA_SQL[\"server\"]='" . $this->EA_Repository->getServer() . "';
                    \$EA_SQL[\"benutzer\"]='" . $this->EA_Repository->getUser() . "';
                    \$EA_SQL[\"passwort\"]='" . $this->EA_Repository->getPassword() . "';
			\n";
        $fp = fOpen($path, "w");
        if ($fp === false) {
            $this->EA_Messages->addMessage("Fehler beim Öffnen der Config-Datei unter " . $path . ". Bitte manuell die Datei config\dbConfigDaten.php bearbeiten.",15563213123,EA_Message::MESSAGE_WARNING);            
            return null;
        }
        
        if (fWrite($fp, $text) === false) {
            $this->EA_Messages->addMessage("Fehler beim Schreiben der SQL Daten unter Pfad " . $path . ". Bitte manuell die Datei config\dbConfigDaten.php bearbeiten.",324345786777,EA_Message::MESSAGE_WARNING);            
            return null;
        }
        $this->EA_Messages->addMessage("Kann die Datei trotz erfolgreich nicht geschrieben werden wurde der Server unter Windows auf C:/ installiert. Durch die Windowspartion extieren dort keine Schreibrechte, esseidenn der Server wird als Admin ausgeführt.",13577459667,EA_Message::MESSAGE_INFO);

        fClose($fp);
        $this->EA_Messages->addMessage("Datenbankdaten unter " . ROOT_PATH . "\config\dbConfigDaten.php erfolgreich gespeichert",13577449667,EA_Message::MESSAGE_SUCCESS);
        return true;
    } 

}
