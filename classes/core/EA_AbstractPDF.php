<?php

namespace CharitySwimRun\classes\core;

use Doctrine\ORM\EntityManager;

use CharitySwimRun\classes\model\EA_Teilnehmer;
use Smarty\Smarty;
use CharitySwimRun\classes\model\EA_AltersklasseRepository;
use CharitySwimRun\classes\model\EA_StreckeRepository;
use CharitySwimRun\classes\model\EA_Konfiguration;
use CharitySwimRun\classes\model\EA_KonfigurationRepository;
use CharitySwimRun\classes\model\EA_TeilnehmerRepository;
use CharitySwimRun\classes\model\EA_Strecke;
use CharitySwimRun\classes\model\EA_Altersklasse;
use CharitySwimRun\classes\model\EA_MannschaftRepository;
use CharitySwimRun\classes\model\EA_VereinRepository;
require_once (ROOT_PATH.'/vendor/autoload.php');

abstract class EA_AbstractPDF extends \TCPDF
{
    protected ?array $startgruppen;
    protected ?array $streckeList;
    protected ?array $altersklasseList = null;
    protected array $geschlechter;
    protected ?EA_Konfiguration $konfiguration;
    protected array $filter = [];
    protected Smarty $smarty;
    protected string $ds;
    protected string $typ;
    protected EA_AltersklasseRepository $altersklasseRepository;
    protected EA_StreckeRepository $streckeRepository;
    protected EA_KonfigurationRepository $EA_KonfigurationRepository;
    protected EA_TeilnehmerRepository $teilnehmerRepository;
    protected EA_VereinRepository $EA_VereinRepository;
    protected EA_MannschaftRepository $EA_MannschaftRepository;


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

        $this->altersklasseRepository = new EA_AltersklasseRepository($entityManager);
        $this->streckeRepository = new EA_StreckeRepository($entityManager);
        $this->EA_VereinRepository = new EA_VereinRepository($entityManager);
        $this->EA_MannschaftRepository = new EA_MannschaftRepository($entityManager);
        $this->teilnehmerRepository = new EA_TeilnehmerRepository($entityManager);
        $this->EA_KonfigurationRepository = new EA_KonfigurationRepository($entityManager);
        $this->konfiguration = $this->EA_KonfigurationRepository->load();
        $this->geschlechter = EA_Teilnehmer::GESCHLECHT_LIST_KURZ;
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
            $ergebnisse = $this->EA_VereinRepository->loadList();
        } elseif ($this->filter['typ'] === "Mannschaften") {
            $ergebnisse = $this->EA_MannschaftRepository->loadList();
        } else {
            $strecke = null;
            $altersklasse = null;
            if($this->filter['strecke']){
                $strecke = new EA_Strecke();
                $strecke->setId($this->filter['strecke']);
            }
            if($this->filter['altersklasse']){
                $altersklasse = new EA_Altersklasse();
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
                        $altersklasse = new EA_Altersklasse();
                        $altersklasse->setId($akkey);
                        $strecke = new EA_Strecke();
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