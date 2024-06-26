<?php
namespace CharitySwimRun\classes\controller;

use Doctrine\ORM\EntityManager;

use CharitySwimRun\classes\helper\EA_PlacementHelper;

class EA_ResultController extends EA_Controller
{
    public function __construct( EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }
    public function getPageErgebnisse(): string
    {
        $content = "";
        $platzierungBerechnenHelper = new EA_PlacementHelper($this->entityManager);
        $specialEvaluation = null;
        //Verarbeiten der Auswahl
        $filter['typ'] = "Einzelstarter";
        $filter['strecke'] = null;
        $filter['altersklasse'] = null;
        $filter['geschlecht'] = null;
        $filter['wertung'] = null;
        if (isset($_POST['ergebnisliste_typ']) && $_POST['ergebnisliste_typ'] === "Einzelstarter") {   
            $teilnehmerList = $this->EA_StarterRepository->loadList(null,null, null,null,null,null,null,'impulseCache',"DESC",true,true);
            $ergebnisse = $platzierungBerechnenHelper->berechnePlatzierung($teilnehmerList);
        } elseif (isset($_POST['ergebnisliste_typ']) && $_POST['ergebnisliste_typ'] === "Mannschaften") {
            $filter['typ'] = "Mannschaften";
            $this->EA_TeamRepository->MannschaftPunkteBerechnen(null,$this->EA_ConfigurationRepository->load());
            $ergebnisseUnsorted = $this->EA_TeamRepository->loadList();
            $ergebnisse = $platzierungBerechnenHelper->quicksort($ergebnisseUnsorted,"getGesamtImpulse");
        } elseif (isset($_POST['ergebnisliste_typ']) && $_POST['ergebnisliste_typ'] === "Vereine") {
            $filter['typ'] = "Vereine";
            $ergebnisseUnsorted = $this->EA_ClubRepository->loadList();
            $ergebnisse = $platzierungBerechnenHelper->quicksort($ergebnisseUnsorted,"getGesamtImpulse");
        } elseif (isset($_POST['ergebnisliste_typ']) && $_POST['ergebnisliste_typ'] === "specialEvaluation") {
            $specialEvaluation = $this->EA_SpecialEvaluationRepository->loadById((int)filter_input(INPUT_POST,"specialEvaluation",FILTER_SANITIZE_NUMBER_INT));
            //only load necassary starter
            $ergebnisseUnsorted = $this->EA_StarterRepository->loadList($specialEvaluation->getStrecke(),$specialEvaluation->getAltersklasse(), $specialEvaluation->getGeschlecht(),null,null,null,null,'gesamtplatz',"ASC",true,true,null,null,null,null,null,null,null,$specialEvaluation);
            $ergebnisse = $platzierungBerechnenHelper->quicksort($ergebnisseUnsorted,"getImpulseSonderwertung", $specialEvaluation);
        } else {
            $teilnehmerList = $this->EA_StarterRepository->loadList(null,null, null,null,null,null,null,'impulseCache',"DESC",true,true);
            $ergebnisse = $platzierungBerechnenHelper->berechnePlatzierung($teilnehmerList);
        }

        $AltersklassenTeilnehmerVerteilung = $this->EA_StarterRepository->loadStreckenAltersklassenTeilnehmerVerteilung(true);
        $StreckenAltersklassenTeilnehmerVerteilung = $this->EA_StarterRepository->loadStreckenAltersklassenTeilnehmerVerteilung();
        $content .= $this->EA_FR->getContentErgebnisse($filter, $ergebnisse, $StreckenAltersklassenTeilnehmerVerteilung, $AltersklassenTeilnehmerVerteilung, $specialEvaluation, $this->EA_SpecialEvaluationRepository,$this->entityManager);
        return $content;
    }

}