<?php
namespace CharitySwimRun\classes\model;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Proxy\ProxyFactory;
use Doctrine\ORM\Tools\SchemaTool;
use Exception;

class EA_Repository{
    private ?EntityManager $entityManager = null;

    private string $database = "";
    private string $user = "";
    private string $password = "";
    private string $server = "";

    private string $tablePrefix = "pcs";

    private const DATABASE = "auswertung";

    public const TB_TRANSPONDER = "transponder";
    public const TB_TEILNEHMER = "teilnehmer";
    public const TB_LOG = "log";
    public const TB_VEREIN = "verein";
    public const TB_CACHE = "cache";

    const TABELLE_LIST = [
        'teilnehmer',
        'konfiguration',
        'specialevaluation',
        'users',
        'aks',
        'strecken',
        'log',
        'verein',
        'mannschaft',
        'mannschaft_kategorien',
        'urkunden',
        'cache',
        'transponder'
    ];

    public function __construct(string $user, string $password, string $server, bool $createConnection=false)
    {
        $this->user = $user;
        $this->password = $password;
        $this->server = $server;
        $this->database = $createConnection === true ? "information_schema" : self::DATABASE;
        $this->createEntityManager();
    }


    public function setEntityManager(EntityManager $entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    public function getEntityManager(): ?EntityManager
    {
        return $this->entityManager;
    }

    public function isDoctrineConnected(): bool
    {
        //this is the only way thats works. isConntected() delivers false results
        try {
            if($this->server === ""){
                return false;
            }
            $connection = $this->entityManager->getConnection();
            $sql = "SELECT 1"; // 
            $stmt = $connection->executeQuery($sql);
            $result = $stmt->fetchOne();
        
            if ($result) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo "Es gab einen Fehler beim Herstellen der Verbindung: " . $e->getMessage();
            return false;
        }
    }

    public function connectDoctrin(): void
    {
        $this->createEntityManager();
    }

    private function createEntityManager(): void
    {
        $config = ORMSetup::createAttributeMetadataConfiguration([ROOT_PATH."/classes"],false,);
        //https://www.doctrine-project.org/projects/doctrine-orm/en/3.1/reference/advanced-configuration.html#query-cache-recommended
        //set false when not developing
        $config->setAutoGenerateProxyClasses(true);
     #   $config->setMetadataCache(new \Symfony\Component\Cache\Adapter\PhpFilesAdapter('doctrine_metadata',0,ROOT_PATH."/doctrineMetaDataCache2"));
    #    $config->setQueryCache(new \Symfony\Component\Cache\Adapter\PhpFilesAdapter('doctrine_queries'));

        $connectionParams = [
            'dbname' => $this->database,
            'user' => $this->user,
            'password' => $this->password,
            'host' => $this->server,
            'driver' => 'pdo_mysql',
        ];

        try{
            $connection = DriverManager::getConnection($connectionParams,$config);
            $this->entityManager = new EntityManager($connection, $config);
        }catch(Exception $e){
            $this->entityManager  = null;
            return;
        }
    }

    public function createDatabase(): void
    {
        $schemaManager = $this->entityManager->getConnection()->createSchemaManager();
        // Check if the database already exists
        if (!in_array(self::DATABASE,$schemaManager->listDatabases())) {
            // Create the database
            $schemaManager->createDatabase(self::DATABASE);
        }
      
         // Create EntityManager with the correct database und set correct database in class
         $this->database = self::DATABASE;
         $this->createEntityManager();
    }


    public function getDatabaseTableList():array
    {
        $schemaManager = $this->entityManager->getConnection()->createSchemaManager();
        return $schemaManager->listTables();
    }

    public function createDatabaseTables(): void
    {
        // SchemaTool instanziieren
        $schemaTool = new SchemaTool($this->entityManager);
        if (count($this->getDatabaseTableList()) > 0) {
            return;
        }
        // Metadaten für die Entitäten abrufen
        $classes = [
            //CREATE VALUES
            $this->entityManager->getClassMetadata(EA_AgeGroup::class),
            $this->entityManager->getClassMetadata(EA_Hit::class),
            $this->entityManager->getClassMetadata(EA_Configuration::class),
            $this->entityManager->getClassMetadata(EA_Team::class),
            $this->entityManager->getClassMetadata(EA_TeamCategory::class),
            $this->entityManager->getClassMetadata(EA_SpecialEvaluation::class),
            $this->entityManager->getClassMetadata(EA_Distance::class),
            $this->entityManager->getClassMetadata(EA_Starter::class),
            $this->entityManager->getClassMetadata(EA_CertificateElement::class),
            $this->entityManager->getClassMetadata(EA_User::class),
            $this->entityManager->getClassMetadata(EA_Club::class),
            $this->entityManager->getClassMetadata(EA_Company::class),
            //only relevant in admin's project
            $this->entityManager->getClassMetadata(EA_Cache::class),
            $this->entityManager->getClassMetadata(EA_RfidChip::class),

           //CREATE VALUES
        ];

        // Schema erstellen
        try {
            $schemaTool->createSchema($classes);
            echo "Die Datenbanktabelle wurde erfolgreich erstellt.";
        } catch (Exception $e) {
            echo "Es gab einen Fehler beim Erstellen der Tabelle: " . $e->getMessage();
        }
    }

    public function resetDatabase(string $modus = "TRUNCATE"): void
    {
        $this->entityManager->getConnection()->prepare("SET FOREIGN_KEY_CHECKS = 0;")->executeQuery();
        $schemaManager = $this->entityManager->getConnection()->createSchemaManager();
        foreach ($schemaManager->listTableNames() as $tableName) {
                //Do not truncate this tables, but drop them if necassary
                if($modus === "TRUNCATE" && ($tableName === "users" || $tableName === "transponder")){
                    continue;
                }
                $sql = ''.$modus.'  TABLE ' . $tableName;
                $this->entityManager->getConnection()->prepare($sql)->executeQuery();
        }
        $this->entityManager->getConnection()->prepare("SET FOREIGN_KEY_CHECKS = 1;")->executeQuery();  
    }

    public function update(): void
    {
        $this->entityManager->flush();
    }
    //performance
    //php.ini -> realpath_cache_size = 64M
  

    //####################################################################################

    public function getDatabase(): string
    {
        return $this->database;
    }

    public function setDatabase(string $database):void
    {
        $this->database = $database;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function setUser(string  $user):void
    {
        $this->user = $user;
    }


    public function getPassword(): string
    {
        return $this->password;
    }


    public function setPassword(string $password):void
    {
        $this->password = $password;
    }

    public function getServer(): string
    {
        return $this->server;
    }

    public function setServer(string $server):void
    {
        $this->server = $server;
    }

    public function getTablePrefix(): string
    {
        return $this->tablePrefix;
    }
}