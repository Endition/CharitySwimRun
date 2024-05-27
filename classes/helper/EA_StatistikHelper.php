<?php
namespace EndeAuswertung\classes\helper;

use Doctrine\DBAL\Connection;
use EndeAuswertung\classes\model\EA_Konfiguration;
use EndeAuswertung\classes\model\EA_Repository;
use EndeAuswertung\classes\model\EA_TeilnehmerRepository;

class EA_StatistikHelper
{

    private Connection $DbalConn;

    private EA_TeilnehmerRepository $EA_TeilnehmerRepository;

    private EA_Konfiguration $konfiguration;

    private array $nullwerte24h = array(
        0, 0, 0, 0, 0, 0,
        0, 0, 0, 0, 0, 0,
        0, 0, 0, 0, 0, 0,
        0, 0, 0, 0, 0, 0);

    public function __construct(EA_Repository $EA_Repository, EA_Konfiguration $konfiguration)
    {
        $this->konfiguration = $konfiguration;
        $this->DbalConn = $EA_Repository->getEntityManager()->getConnection();
        
        $this->EA_TeilnehmerRepository = new EA_TeilnehmerRepository($EA_Repository->getEntityManager());
    }

    public function loadStatistikData(string $typ, ?int $id=null): array
    {
        $query = $this->getStatstikQuery($typ, $id);
        
        $result =$this->DbalConn->executeQuery($query);
        $data = $result->fetchAllAssociative();

        return $this->formatData($typ, $data);
    }

    private function getStatstikQuery(string $typ, ?int $id): string
    {
        if($typ === "TNproLeser"){
            $query = "SELECT Leser, COUNT(DISTINCT TeilnehmerId) AS 'Anzahl'  FROM " . EA_Repository::TB_LOG . " WHERE Timestamp >  UNIX_TIMESTAMP()-180 AND geloescht = 0  GROUP BY Leser ORDER BY Leser ASC;";
        }elseif($typ === "BuchungenProStunde"){
            $query = "SELECT Startzeit, HOUR(FROM_UNIXTIME(Timestamp, '%Y-%m-%d %H.%i.%s')) AS 'Stunde', DAY(FROM_UNIXTIME(Timestamp, '%Y-%m-%d %H.%i.%s')) AS 'Tag', COUNT(Timestamp) AS 'Anzahl'
                        FROM " . EA_Repository::TB_LOG . "
                        INNER JOIN " . EA_Repository::TB_TEILNEHMER . " ON " . EA_Repository::TB_TEILNEHMER . ".Id = " . EA_Repository::TB_LOG . ".TeilnehmerId \n
                        WHERE geloescht = 0 \n ";
                        if ($id) {
                            $query .= " AND " . EA_Repository::TB_LOG . ".TeilnehmerId = " . $id . " \n";
                        }
            $query .= " AND (Timestamp >= UNIX_TIMESTAMP(Startzeit) OR Timestamp IS NULL) \n";
            $query .= "  GROUP BY Tag,Stunde  ORDER BY Tag, Stunde ASC; \n";
        }elseif($typ === "BuchungenProLeser"){
            $query = "SELECT Leser, COUNT(Leser) AS 'Anzahl' FROM " . EA_Repository::TB_LOG . " WHERE geloescht = 0 GROUP BY Leser ORDER BY Leser ASC;";
        }elseif($typ === "BuchungenProStundeUndLeser"){
            $query = "SELECT Leser, HOUR(FROM_UNIXTIME(Timestamp, '%Y-%m-%d %H.%i.%s')) AS 'Stunde', DAY(FROM_UNIXTIME(Timestamp, '%Y-%m-%d %H.%i.%s')) AS 'Tag', COUNT(Timestamp) AS 'Anzahl' FROM " . EA_Repository::TB_LOG . " WHERE geloescht = 0  GROUP BY Leser, Stunde, Tag ORDER BY Tag, Stunde, Leser ASC;";
        }elseif($typ === "GestarteteTNproStunde"){
            $query = "SELECT HOUR(Startzeit) AS 'Stunde', DAY(Startzeit) AS 'Tag', COUNT(Startzeit) AS 'Anzahl' FROM " . EA_Repository::TB_TEILNEHMER . " GROUP BY Stunde, Tag ORDER BY Tag, Stunde ASC;";
        }elseif($typ === "AktiveTNproStunde"){
            $query = "SELECT COUNT(DISTINCT TeilnehmerId) AS 'Anzahl', HOUR(FROM_UNIXTIME(Timestamp, '%Y-%m-%d %H.%i.%s')) AS 'Stunde',DAY(FROM_UNIXTIME(Timestamp, '%Y-%m-%d %H.%i.%s')) AS 'Tag' FROM " . EA_Repository::TB_LOG . " WHERE geloescht = 0 GROUP BY Stunde ORDER BY Stunde ASC;";
        }elseif($typ === "Performance"){
            $query = "SELECT MINUTE(FROM_UNIXTIME(Timestamp, '%Y-%m-%d %H.%i.%s')) AS 'Minute',  COUNT(Timestamp) AS 'Anzahl' FROM " . EA_Repository::TB_LOG . " WHERE geloescht = 0   AND from_unixtime(timestamp) > DATE_SUB(NOW(), INTERVAL 15 MINUTE) GROUP BY Minute ORDER BY Minute ASC;";
        }
        return $query;
    }

    private function formatData(string $typ, array $data): array
    {
      
        if($typ === "TNproLeser"){
            $daten = [];
            foreach ($data as $row) {
                $daten[] = ['abkuerzung' => "Leser " . $row['Leser'], 'anzahl' => $row['Anzahl']];
            }
        }elseif($typ === "BuchungenProStunde"){
            $zwischenspeichern = [];
            foreach ($data as $row) {
                $zwischenspeichern[$row['Tag']][$row['Stunde']] = $row['Anzahl'] * $this->konfiguration->getFaktor();
            }
            $daten = $this->test($zwischenspeichern);
        }elseif($typ === "BuchungenProLeser"){
            $daten = [];
            foreach ($data as $row) {
                $daten[] = ['abkuerzung' => "Leser " . $row['Leser'], 'anzahl' => $row['Anzahl'] * $this->konfiguration->getFaktor() ];
            }
        }elseif($typ === "BuchungenProStundeUndLeser"){
                $zwischenspeichern = [];
                foreach ($data as $row) {
                     $zwischenspeichern["Tag: " . $row['Tag'] . ". Leser: " . $row['Leser']][$row['Stunde']] = $row['Anzahl'] * $this->konfiguration->getFaktor();
                 }
                $daten = $this->test($zwischenspeichern);
            
        }elseif($typ === "GestarteteTNproStunde" || $typ === "AktiveTNproStunde"){
                $zwischenspeichern = [];
                foreach ($data as $row) {
                    $zwischenspeichern[$row['Tag']][$row['Stunde']] = $row['Anzahl'];
                }
                $daten = $this->test($zwischenspeichern);
        }elseif($typ === "Performance"){
            $zwischenspeichern = [];
                foreach ($data as $row) {
                    $zwischenspeichern['0'][$row['Minute']] = $row['Anzahl'];
                }
                $daten['datasets'] = [];
                foreach ($zwischenspeichern as $minute => $anzahl) {
                    $daten['datasets'][] = ["label"=>"Minute: ".$minute,"data"=>$anzahl];

                }
        }
        return $daten;
    }

    private function test(array $zwischenspeichern): array
    {
        $daten['datasets'] = [];
        foreach ($zwischenspeichern as $tag => $anzahl) { 
            // Nullwerte mit Werte kombinieren anschließend neue nach Arraykey sortieren
            // Durach das Addieren der beiden Array werden die fehlenden Werte aufgefüllt. Damit Größe 24 erreicht wird
            $anzahl = $anzahl + $this->nullwerte24h;
            ksort($zwischenspeichern[$tag]);
            $daten['datasets'][] = ["label"=>"Tag: ".$tag.".","data"=>$anzahl];
        }
        return $daten;
    }
}