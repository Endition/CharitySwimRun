<?php
namespace CharitySwimRun\classes\model;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Proxy\ProxyFactory;
use Doctrine\ORM\Tools\SchemaTool;
use Exception;

class EA_Repository
{
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
    public const TB_UNTERNEHMEN = "unternehmen";
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
        'unternehmen',
        'mannschaft',
        'mannschaft_kategorien',
        'urkunden',
        'cache',
        'transponder',
        'femalefirstnames',
    ];

    public function __construct(string $user, string $password, string $server, bool $createConnection = false)
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
            if ($this->server === "") {
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
        $isDevMode = true; // Default fallback

        // Detect Developer Mode before creating ORM
        try {
            $dsn = "mysql:host={$this->server};dbname=" . self::DATABASE . ";charset=utf8mb4";
            $pdo = new \PDO($dsn, $this->user, $this->password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_SILENT]);

            $stmt = $pdo->query("SELECT entwicklermodus FROM konfiguration LIMIT 1");
            if ($stmt) {
                $res = $stmt->fetch(\PDO::FETCH_ASSOC);
                if ($res) {
                    $isDevMode = (bool) $res['entwicklermodus'];
                }
            }
        } catch (\Exception $e) {
            // Table might not exist during setup
        }

        $config = ORMSetup::createAttributeMetadataConfiguration([ROOT_PATH . "/classes"], $isDevMode);
        //https://www.doctrine-project.org/projects/doctrine-orm/en/3.1/reference/advanced-configuration.html#query-cache-recommended
        // Manuell den Cache löschen: C:\laragon\www\CharitySwimRun\doctrineMetaDataCache2\doctrine_metadata
        if ($isDevMode) {
            $config->setAutoGenerateProxyClasses(true);
        } else {
            $config->setAutoGenerateProxyClasses(false);
            // NOTE: If you change your Entity classes, you may need to clear this directory manually:
            // C:\Users\matze\CharitySwimRun\doctrineMetaDataCache2\doctrine_metadata
            $config->setMetadataCache(new \Symfony\Component\Cache\Adapter\PhpFilesAdapter('doctrine_metadata', 0, ROOT_PATH . "/doctrineMetaDataCache2"));
            $config->setQueryCache(new \Symfony\Component\Cache\Adapter\PhpFilesAdapter('doctrine_queries'));
        }

        $connectionParams = [
            'dbname' => $this->database,
            'user' => $this->user,
            'password' => $this->password,
            'host' => $this->server,
            'driver' => 'pdo_mysql',
            'charset' => 'utf8mb4',
        ];

        try {
            $connection = DriverManager::getConnection($connectionParams, $config);
            $connection->executeStatement("SET NAMES 'utf8mb4'");
            $this->entityManager = new EntityManager($connection, $config);
        } catch (Exception $e) {
            echo "Es gab einen Fehler beim Herstellen der Verbindung: " . $e->getMessage();
            $this->entityManager = null;
            return;
        }
    }

    public function createDatabase(): void
    {
        $schemaManager = $this->entityManager->getConnection()->createSchemaManager();
        // Check if the database already exists
        if (!in_array(self::DATABASE, $schemaManager->listDatabases())) {
            // Create the database
            $schemaManager->createDatabase(self::DATABASE);
        }

        // Create EntityManager with the correct database und set correct database in class
        $this->database = self::DATABASE;
        $this->createEntityManager();
    }


    public function getDatabaseTableList(): array
    {
        $schemaManager = $this->entityManager->getConnection()->createSchemaManager();
        return $schemaManager->listTables();
    }

    /**
     * Creates the database tables based on the entity metadata.
     * Automatically installs necessary database triggers after schema creation.
     * 
     * @return void
     */
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
            $this->entityManager->getClassMetadata(EA_FemaleFirstname::class),
            //only relevant in admin's project
            $this->entityManager->getClassMetadata(EA_Cache::class),
            $this->entityManager->getClassMetadata(EA_RfidChip::class),

            //CREATE VALUES
        ];

        // Schema erstellen
        try {
            $schemaTool->createSchema($classes);
            $this->installTriggers();
            $this->installEvents();
            echo "Die Datenbanktabelle wurde erfolgreich erstellt.";
        } catch (Exception $e) {
            echo "Es gab einen Fehler beim Erstellen der Tabelle: " . $e->getMessage();
        }
    }

    private function installTriggers(): void
    {
        $conn = $this->entityManager->getConnection();

        // 1. Trigger for INSERT on log (updates impulseCache)
        $conn->executeStatement("DROP TRIGGER IF EXISTS trg_hit_insert");
        $conn->executeStatement("
            CREATE TRIGGER trg_hit_insert
            AFTER INSERT ON log
            FOR EACH ROW
            BEGIN
                IF NEW.geloescht = 0 AND NEW.TeilnehmerId IS NOT NULL THEN
                    UPDATE teilnehmer SET impulseCache = impulseCache + 1 WHERE id = NEW.TeilnehmerId;
                END IF;
            END
        ");

        // 2. Trigger for UPDATE on log (updates impulseCache)
        $conn->executeStatement("DROP TRIGGER IF EXISTS trg_hit_update");
        $conn->executeStatement("
            CREATE TRIGGER trg_hit_update
            AFTER UPDATE ON log
            FOR EACH ROW
            BEGIN
                IF OLD.geloescht = 0 AND NEW.geloescht = 1 THEN
                    IF OLD.TeilnehmerId IS NOT NULL THEN
                        UPDATE teilnehmer SET impulseCache = impulseCache - 1 WHERE id = OLD.TeilnehmerId;
                    END IF;
                ELSEIF OLD.geloescht = 1 AND NEW.geloescht = 0 THEN
                    IF NEW.TeilnehmerId IS NOT NULL THEN
                        UPDATE teilnehmer SET impulseCache = impulseCache + 1 WHERE id = NEW.TeilnehmerId;
                    END IF;
                ELSEIF OLD.geloescht = 0 AND NEW.geloescht = 0 AND (OLD.TeilnehmerId != NEW.TeilnehmerId OR (OLD.TeilnehmerId IS NULL AND NEW.TeilnehmerId IS NOT NULL) OR (OLD.TeilnehmerId IS NOT NULL AND NEW.TeilnehmerId IS NULL)) THEN
                    IF OLD.TeilnehmerId IS NOT NULL THEN
                        UPDATE teilnehmer SET impulseCache = impulseCache - 1 WHERE id = OLD.TeilnehmerId;
                    END IF;
                    IF NEW.TeilnehmerId IS NOT NULL THEN
                        UPDATE teilnehmer SET impulseCache = impulseCache + 1 WHERE id = NEW.TeilnehmerId;
                    END IF;
                END IF;
            END
        ");

        // 3. Trigger for DELETE on log (updates impulseCache)
        $conn->executeStatement("DROP TRIGGER IF EXISTS trg_hit_delete");
        $conn->executeStatement("
            CREATE TRIGGER trg_hit_delete
            AFTER DELETE ON log
            FOR EACH ROW
            BEGIN
                IF OLD.geloescht = 0 AND OLD.TeilnehmerId IS NOT NULL THEN
                    UPDATE teilnehmer SET impulseCache = impulseCache - 1 WHERE id = OLD.TeilnehmerId;
                END IF;
            END
        ");

        $statusStartunterlagen = EA_Starter::STATUS_STARTUNTERLAGEN_ABHEHOLT;
        $statusAufDerStrecke = EA_Starter::STATUS_AUF_DER_STRECKE;
        $statusZurueckgegeben = EA_Starter::STATUS_TRANSPONDER_ZURUECKGEGEBEN;

        // 4. Trigger for cache processing (RFID Hardware Bridge)
        $conn->executeStatement("DROP TRIGGER IF EXISTS trg_cache_insert");
        $conn->executeStatement("
            CREATE TRIGGER trg_cache_insert
            BEFORE INSERT ON cache
            FOR EACH ROW
            BEGIN
                DECLARE v_TeilnehmerId INT;
                DECLARE v_TransponderId INT;
                DECLARE v_LastTimestamp INT;
                DECLARE v_LockoutTime INT DEFAULT 10;
                DECLARE v_Status INT;
                DECLARE v_Startzeit DATETIME;
                DECLARE v_Starttyp VARCHAR(255);

                -- Mark as processed immediately
                SET NEW.verarbeitet = 1;

                -- Find participant and transponder info in one go
                -- We only look for participants who haven't returned their transponder yet
                SELECT t.id, tr.Transpondernummer, t.Status, t.Startzeit, k.buchungssperre, k.starttyp
                INTO v_TeilnehmerId, v_TransponderId, v_Status, v_Startzeit, v_LockoutTime, v_Starttyp
                FROM transponder tr
                JOIN teilnehmer t ON t.transponder = tr.Transpondernummer
                CROSS JOIN konfiguration k
                WHERE tr.Transponderschluessel = NEW.Transponderschluessel
                  AND t.Status < $statusZurueckgegeben
                ORDER BY t.id DESC
                LIMIT 1;

                IF v_TeilnehmerId IS NOT NULL THEN
                    -- 1. Status Check: Only allow if participant has picked up documents or is already on track
                    IF v_Status >= $statusStartunterlagen THEN
                        
                        -- 2. Autostart logic (ade - Autostart durch Einbuchen)
                        -- If no start time is set yet and mode is ade, set it now
                        IF v_Starttyp = 'ade' AND v_Startzeit IS NULL THEN
                            UPDATE teilnehmer 
                            SET Startzeit = FROM_UNIXTIME(NEW.Buchungszeit), 
                                Status = $statusAufDerStrecke
                            WHERE id = v_TeilnehmerId;
                            SET v_Startzeit = FROM_UNIXTIME(NEW.Buchungszeit);
                        END IF;

                        -- 3. Timing checks (only if we have a start time)
                        IF v_Startzeit IS NOT NULL THEN
                            -- Check if scan is AFTER start time (ignore pre-start noise)
                            IF NEW.Buchungszeit >= UNIX_TIMESTAMP(v_Startzeit) THEN
                                
                                -- 4. Duplicate/Lockout check
                                SET v_LastTimestamp = NULL;
                                SELECT Timestamp INTO v_LastTimestamp 
                                FROM log 
                                WHERE TeilnehmerId = v_TeilnehmerId 
                                  AND geloescht = 0 
                                  AND Timestamp <= NEW.Buchungszeit 
                                  AND Timestamp > (NEW.Buchungszeit - v_LockoutTime)
                                LIMIT 1;

                                IF v_LastTimestamp IS NULL THEN
                                    -- Store NULL for TransponderId when TeilnehmerId is known to avoid redundancy
                                    INSERT INTO log (TeilnehmerId, TransponderId, Timestamp, Leser, geloescht)
                                    VALUES (v_TeilnehmerId, NULL, NEW.Buchungszeit, NEW.Leser, 0);
                                END IF;
                            END IF;
                        END IF;
                    END IF;
                ELSE
                    -- Fallback: Log transponders that are in the database but not assigned to an active participant
                    -- This helps identifying unregistered starters or hardware issues
                    SELECT Transpondernummer INTO v_TransponderId 
                    FROM transponder 
                    WHERE Transponderschluessel = NEW.Transponderschluessel 
                    LIMIT 1;
                    
                    IF v_TransponderId IS NOT NULL THEN
                         -- Check if it's already logged recently to avoid spamming the log
                         SET v_LastTimestamp = NULL;
                         SELECT Timestamp INTO v_LastTimestamp 
                         FROM log 
                         WHERE TransponderId = v_TransponderId 
                           AND TeilnehmerId IS NULL
                           AND geloescht = 0 
                           AND Timestamp <= NEW.Buchungszeit 
                           AND Timestamp > (NEW.Buchungszeit - 60) -- 1 minute lockout for unassigned chips
                         LIMIT 1;

                         IF v_LastTimestamp IS NULL THEN
                            INSERT INTO log (TeilnehmerId, TransponderId, Timestamp, Leser, geloescht)
                            VALUES (NULL, v_TransponderId, NEW.Buchungszeit, NEW.Leser, 0);
                         END IF;
                    END IF;
                END IF;
            END
        ");
    }

    /**
     * Installs MySQL Events for scheduled tasks.
     * Specifically, it creates an event to recalculate participant rankings every 3 minutes.
     * 
     * @return void
     */
    private function installEvents(): void
    {
        $conn = $this->entityManager->getConnection();

        // Ensure the event scheduler is enabled
        $conn->executeStatement("SET GLOBAL event_scheduler = ON");

        // Event for ranking calculation every 3 minutes
        $conn->executeStatement("DROP EVENT IF EXISTS e_aktualisiere_ranking");
        $conn->executeStatement("
            CREATE EVENT e_aktualisiere_ranking
            ON SCHEDULE EVERY 3 MINUTE
            DO
            BEGIN
                -- 1. Update participant rankings
                UPDATE teilnehmer t
                JOIN (
                    SELECT
                        id,
                        RANK() OVER (PARTITION BY Geschlecht ORDER BY impulseCache DESC)                         AS gesamt_rank,
                        RANK() OVER (PARTITION BY Geschlecht, Strecke ORDER BY impulseCache DESC)                AS strecken_rank,
                        RANK() OVER (PARTITION BY Geschlecht, Strecke, Altersklasse ORDER BY impulseCache DESC) AS ak_rank
                    FROM teilnehmer
                ) r ON t.id = r.id
                SET
                    t.Gesamtplatz   = r.gesamt_rank,
                    t.Streckenplatz = r.strecken_rank,
                    t.AKPlatz       = r.ak_rank;

                -- 2. Synchronize the last calculation count to avoid redundant PHP-side triggers
                UPDATE konfiguration 
                SET lastCalculationResultsNumber = (SELECT COUNT(*) FROM log WHERE geloescht = 0)
                LIMIT 1;
            END
        ");
    }

    /**
     * Checks if all required triggers and events exist in the database.
     * 
     * @return array An associative array with 'status' (bool) and 'missing' (array of names).
     */
    public function checkDatabaseIntegrity(): array
    {
        $conn = $this->entityManager->getConnection();
        $missing = [];

        // Required Triggers
        $requiredTriggers = ['trg_hit_insert', 'trg_hit_update', 'trg_hit_delete', 'trg_cache_insert'];
        $existingTriggers = $conn->iterateAssociative("SHOW TRIGGERS");
        $foundTriggers = [];
        foreach ($existingTriggers as $t) {
            $foundTriggers[] = $t['Trigger'];
        }

        foreach ($requiredTriggers as $rt) {
            if (!in_array($rt, $foundTriggers)) {
                $missing[] = "Trigger: $rt";
            }
        }

        // Required Events
        $requiredEvents = ['e_aktualisiere_ranking'];
        $existingEvents = $conn->iterateAssociative("SHOW EVENTS");
        $foundEvents = [];
        foreach ($existingEvents as $e) {
            $foundEvents[] = $e['Name'];
        }

        foreach ($requiredEvents as $re) {
            if (!in_array($re, $foundEvents)) {
                $missing[] = "Event: $re";
            }
        }

        return [
            'status' => empty($missing),
            'missing' => $missing
        ];
    }

    /**
     * Re-installs all triggers and events to fix integrity issues.
     * 
     * @return void
     */
    public function repairDatabaseIntegrity(): void
    {
        $this->installTriggers();
        $this->installEvents();
    }

    /**
     * Resets the database tables based on the specified mode.
     * 
     * @param string $modus The reset mode: 'TRUNCATE' (default), 'RESETEVENT', or 'DROP'.
     * @return void
     */
    public function resetDatabase(string $modus = "TRUNCATE"): void
    {
        $connection = $this->entityManager->getConnection();
        $connection->executeStatement("SET FOREIGN_KEY_CHECKS = 0;");
        $schemaManager = $connection->createSchemaManager();

        foreach ($schemaManager->listTableNames() as $tableName) {
            // Keep system configuration and base data if only resetting the event
            if ($modus === "RESETEVENT" && in_array($tableName, ['konfiguration', 'specialevaluation', 'users', 'aks', 'strecken', 'verein', 'unternehmen', 'mannschaft', 'mannschaft_kategorien', 'urkunden', 'transponder', 'femalefirstnames'])) {
                continue;
            }

            // Do not truncate system tables
            if ($modus === "TRUNCATE" && in_array($tableName, ["users", "transponder", "femalefirstnames"])) {
                continue;
            }

            $command = ($modus === "DROP") ? "DROP" : "TRUNCATE";
            $sql = $command . " TABLE " . $connection->quoteIdentifier($tableName);
            $connection->executeStatement($sql);
        }

        $connection->executeStatement("SET FOREIGN_KEY_CHECKS = 1;");
    }

    public function update(): bool
    {
        $this->entityManager->flush();
        return true;
    }
    //performance
    //php.ini -> realpath_cache_size = 64M


    //####################################################################################

    public function getDatabase(): string
    {
        return $this->database;
    }

    public function setDatabase(string $database): void
    {
        $this->database = $database;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function setUser(string $user): void
    {
        $this->user = $user;
    }


    public function getPassword(): string
    {
        return $this->password;
    }


    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getServer(): string
    {
        return $this->server;
    }

    public function setServer(string $server): void
    {
        $this->server = $server;
    }

    public function getTablePrefix(): string
    {
        return $this->tablePrefix;
    }
}
