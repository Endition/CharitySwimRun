<?php

namespace CharitySwimRun\classes\core;

use Doctrine\ORM\EntityManager;

use CharitySwimRun\classes\model\EA_Starter;
use Smarty\Smarty;
use CharitySwimRun\classes\model\EA_AgeGroupRepository;
use CharitySwimRun\classes\model\EA_DistanceRepository;
use CharitySwimRun\classes\model\EA_Configuration;
use CharitySwimRun\classes\model\EA_ConfigurationRepository;
use CharitySwimRun\classes\model\EA_StarterRepository;
use CharitySwimRun\classes\model\EA_Distance;
use CharitySwimRun\classes\model\EA_AgeGroup;
use CharitySwimRun\classes\model\EA_TeamRepository;
use CharitySwimRun\classes\model\EA_ClubRepository;
require_once (ROOT_PATH.'/vendor/autoload.php');

abstract class EA_AbstractPDF extends \TCPDF
{
    protected ?array $startgruppen;
    protected ?array $streckeList;
    protected ?array $altersklasseList = null;
    protected array $geschlechter;
    protected ?EA_Configuration $konfiguration;
    protected array $filter = [];
    protected Smarty $smarty;
    protected string $ds;
    protected string $typ;
    protected EA_AgeGroupRepository $altersklasseRepository;
    protected EA_DistanceRepository $streckeRepository;
    protected EA_ConfigurationRepository $EA_ConfigurationRepository;
    protected EA_StarterRepository $teilnehmerRepository;
    protected EA_ClubRepository $EA_ClubRepository;
    protected EA_TeamRepository $EA_TeamRepository;


    public function __construct(string $orientierung, string  $format, EntityManager $entityManager)
    {
        parent::__construct($orientierung, 'mm', $format, true, 'UTF-8', false);
        // Automatischer Zeilenumbruch mit 0,5 cm Abstand zum unteren Rand
        // Deaktivieren von Header und Footer
        $this->setPrintHeader(true);
        $this->setPrintFooter(true);
        // Setzten der Dokumenteninformationen
        $this->SetCreator("CharitySwimRun");
        $this->SetAuthor('');

        $this->altersklasseRepository = new EA_AgeGroupRepository($entityManager);
        $this->streckeRepository = new EA_DistanceRepository($entityManager);
        $this->EA_ClubRepository = new EA_ClubRepository($entityManager);
        $this->EA_TeamRepository = new EA_TeamRepository($entityManager);
        $this->teilnehmerRepository = new EA_StarterRepository($entityManager);
        $this->EA_ConfigurationRepository = new EA_ConfigurationRepository($entityManager);
        $this->konfiguration = $this->EA_ConfigurationRepository->load();
        $this->geschlechter = EA_Starter::GESCHLECHT_LIST_KURZ;
        $this->altersklasseList = $this->altersklasseRepository->loadList("uDatum");
        $this->streckeList = $this->streckeRepository->loadList("bezLang");
        $this->startgruppen = $this->konfiguration->getStartgruppenAsArray();
        
        $this->ds = DIRECTORY_SEPARATOR;
        $this->smarty = new Smarty();
        $this->smarty
            ->setTemplateDir(dirname($_SERVER["SCRIPT_FILENAME"]) . $this->ds . "classes" . $this->ds . "renderer" . $this->ds . "templates" . $this->ds)
            ->setCompileDir(dirname($_SERVER["SCRIPT_FILENAME"]) . $this->ds . "classes" . $this->ds . "renderer" . $this->ds . "templates_c" . $this->ds)
            ->setCacheDir(dirname($_SERVER["SCRIPT_FILENAME"]) . $this->ds . "classes" . $this->ds . "renderer" . $this->ds . "cache" . $this->ds)
            ->setConfigDir(dirname($_SERVER["SCRIPT_FILENAME"]) . $this->ds . "classes" . $this->ds . "renderer" . $this->ds . "configs" . $this->ds);
        $this->smarty->debugging = false;
        $this->smarty->assign('konfiguration', $this->konfiguration);
        $this->smarty->assign('geschlechter', $this->geschlechter);
        $this->smarty->assign('altersklassen', $this->altersklasseList);
        $this->smarty->assign('strecken', $this->streckeList);
        $this->smarty->assign('startgruppen', $this->startgruppen);
        $this->smarty->assign('ausgabetyp', "PDF");
    }

    /**
     * Überschreiben der Standard-Methode, da diese ansonsten im Header eine
     * Linie erzeugt. Wenn der Header ausgegeben werden soll gibt dieser ein
     * Wasserzeichentext aus.
     */
    public function Header()
    {
        // Logo
        #$image_file = K_PATH_IMAGES.'logo_example.jpg';
        #$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    }

    /**
     * Überschreiben der Standard-Methode, da diese ansonsten im Footer eine
     * Linie erzeugt. Methode kann in den abgeleiteten Klassen überschrieben
     * werden.
     */
    public function Footer()
    {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', '', 8);
        // Page number
        $this->Cell(0, 10, 'Seite ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages() . ' - erstellt am:' . date("d.m.Y H:i") . ' Uhr mit CharitySwimRun', 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }


    protected function InhaltOben(): ?array
    { 
        if ($this->filter['alle'] === null) {
            $ergebnisse = $this->loadDataAlleIsNull();
            if (count($ergebnisse) > 0) {
                $this->smarty->assign('ergebnisse', $ergebnisse);
            }else{
                $this->smarty->assign('ergebnisse', []);
            }
            $this->getContent();
        } else {
            //getContent is inside the Method
            $ergebnisse = $this->loadDataAlleIsNotNull();
        }
        return $ergebnisse;
    }

    /**
     * load a single list with starters, without clustering
     */
    private function loadDataAlleIsNull(): array
    {
        if ($this->filter['typ'] === "Vereine") {
            $ergebnisse = $this->EA_ClubRepository->loadList();
        } elseif ($this->filter['typ'] === "Mannschaften") {
            $ergebnisse = $this->EA_TeamRepository->loadList();
        } else {
            $strecke = null;
            $altersklasse = null;
            if($this->filter['strecke']){
                $strecke = new EA_Distance();
                $strecke->setId($this->filter['strecke']);
            }
            if($this->filter['altersklasse']){
                $altersklasse = new EA_AgeGroup();
                $altersklasse->setId($this->filter['altersklasse']);
            }
            $ergebnisse = $this->teilnehmerRepository->loadList($strecke,$altersklasse,$this->filter['geschlecht'],null,null,null,null,$this->filter["order"],"ASC",null,null,null, $this->filter['status'],$this->filter['id']);   
        }
        return $ergebnisse;
    }

    /**
     * in this case the starters are clustered in distances and classes. In contrast to certificates were in alle cases no clustering ist needed
     */
    private function loadDataAlleIsNotNull(): array
    {
        if ($this->filter['alle'] === "strecken") {
            foreach ($this->streckeList as $strecke) {
                $ergebnisse = $this->teilnehmerRepository->loadList($strecke);
                if (is_array($ergebnisse) && count($ergebnisse) > 0) {
                    $this->smarty->assign('ergebnisse', $ergebnisse);
                    //generating content directly
                    $this->getContent("Strecke: " . $strecke->getBezLang());
                }
            }
        } elseif ($this->filter['alle'] === "altersklassen") {
            foreach ($this->altersklasseList as $altersklasse) {
                foreach ($this->geschlechter as $geschlecht) {
                    $ergebnisse = $this->teilnehmerRepository->loadList(null,$altersklasse,$geschlecht);
                    if (is_array($ergebnisse) && count($ergebnisse) > 0) {
                        $this->smarty->assign('ergebnisse', $ergebnisse);
                        $this->getContent("Altersklasse: " . $altersklasse->getAltersklasse() . " " . $geschlecht);
                    }
                }
            }
        } elseif ($this->filter['alle'] === "streckenxaltersklassen") {
            $verteilung = $this->teilnehmerRepository->loadStreckenAltersklassenTeilnehmerVerteilung();
            foreach ($verteilung as $streckekey => $streckenunterteilung) {
                foreach ($streckenunterteilung['Unterteilung'] as $akkey => $akunterteilung) {
                    foreach ($akunterteilung['Unterteilung'] as $geschlechtkey => $geschlechtunterteilung) {
                        $altersklasse = new EA_AgeGroup();
                        $altersklasse->setId($akkey);
                        $strecke = new EA_Distance();
                        $strecke->setId($streckekey);
                        $ergebnisse = $this->teilnehmerRepository->loadList(null,$altersklasse,$geschlechtkey);
                        if (is_array($ergebnisse) && count($ergebnisse) > 0) {
                            $this->smarty->assign('ergebnisse', $ergebnisse);
                            $this->getContent("Strecke: " . $streckenunterteilung['Bezeichnung'] . " Altersklasse: " . $akunterteilung['Bezeichnung'] . " " . $geschlechtkey);
                        }
                    }
                }
            }

        }
        return $ergebnisse;
    }

    protected function getContent(?string $ueberschrift = null): void
    {
        //overwrite through heritage
    }

}