<?php

namespace CharitySwimRun\classes\renderer;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;

use CharitySwimRun\classes\helper\EA_Helper;
use CharitySwimRun\classes\model\EA_AgeGroup;
use CharitySwimRun\classes\model\EA_AgeGroupRepository;

use CharitySwimRun\classes\model\EA_Configuration;
use CharitySwimRun\classes\model\EA_ConfigurationRepository;
use CharitySwimRun\classes\model\EA_Team;
use CharitySwimRun\classes\model\EA_TeamRepository;
use CharitySwimRun\classes\model\EA_TeamCategory;
use CharitySwimRun\classes\model\EA_Repository;
use CharitySwimRun\classes\model\EA_SpecialEvaluation;
use CharitySwimRun\classes\model\EA_SpecialEvaluationRepository;
use CharitySwimRun\classes\model\EA_Distance;
use CharitySwimRun\classes\model\EA_DistanceRepository;
use CharitySwimRun\classes\model\EA_Club;
use CharitySwimRun\classes\model\EA_Company;
use CharitySwimRun\classes\model\EA_Company_Repository;
use CharitySwimRun\classes\model\EA_Starter;
use CharitySwimRun\classes\model\EA_StarterRepository;
use CharitySwimRun\classes\model\EA_Certificate;
use CharitySwimRun\classes\model\EA_CertificateElement;
use CharitySwimRun\classes\model\EA_User;
use CharitySwimRun\classes\model\EA_ClubRepository;
use CharitySwimRun\classes\model\EA_CompanyRepository;
use Smarty\Smarty;

class EA_FormRenderer extends EA_AbstractRenderer {
    
    private EA_Helper $EA_H;
    private string $ds;
    private Smarty $smarty;

    public function __construct() {
        parent::__construct();
        $this->EA_H = new EA_Helper();
        $this->ds = DIRECTORY_SEPARATOR;
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir(dirname(__FILE__) . "" . $this->ds . "templates" . $this->ds)
                ->setCompileDir(dirname(__FILE__) . "" . $this->ds . "templates_c" . $this->ds)
                ->setCacheDir(dirname(__FILE__) . "" . $this->ds . "cache" . $this->ds)
                ->setConfigDir(dirname(__FILE__) . "" . $this->ds . "configs" . $this->ds);
        $this->smarty->debugging = false;
        //$this->tplpfad = dirname(__FILE__)."".$this->ds."templates".$this->ds;
    }

    private function getStandardIncludes(EntityManager $entityManager, array $includes = []) {
        $altersklasseRepository = new EA_AgeGroupRepository($entityManager);
        $streckeRepository = new EA_DistanceRepository($entityManager);
        $vereinRepository = new EA_ClubRepository($entityManager);
        $unternehmenRepository = new EA_CompanyRepository($entityManager);
        $mannschaftRepository = new EA_TeamRepository($entityManager);
        $konfigurationRepository = new EA_ConfigurationRepository($entityManager);
        $konfiguration = $konfigurationRepository->load();
        if (isset($includes['konfiguration'])) {
            $this->smarty->assign('konfiguration', $konfiguration);
        }
        if (isset($includes['mannschaften'])) {
            $this->smarty->assign('mannschaften', $mannschaftRepository->getListForSelectField());
        }
        if (isset($includes['vereine'])) {
            $this->smarty->assign('vereine', $vereinRepository->loadList("verein"));
        }
        if (isset($includes['unternehmen'])) {
            $this->smarty->assign('unternehmen', $unternehmenRepository->loadList("unternehmen"));
        }
        if (isset($includes['strecken'])) {
            $this->smarty->assign('strecken', $streckeRepository->getListForSelectField());
        }
        if (isset($includes['streckenV1'])) {
            $this->smarty->assign('strecken', $streckeRepository->loadList());
        }
        if (isset($includes['altersklassen'])) {
            $this->smarty->assign('altersklassen', $altersklasseRepository->loadList("uDatum"));
        }
        if (isset($includes['startgruppen'])) {
            $this->smarty->assign('startgruppen', $konfiguration->getStartgruppenAsArray());
        }
        if (isset($includes['geschlechter'])) {
            $this->smarty->assign('geschlechter', EA_Starter::GESCHLECHT_LIST);
        }
        if (isset($includes['geschlechterKurz'])) {
            $this->smarty->assign('geschlechter', EA_Starter::GESCHLECHT_LIST_KURZ);
        }
        if (isset($includes['stati'])) {
            $this->smarty->assign("stati", EA_Starter::STATUS_LIST);
        }
    }

    public function getFormDatabaseData(EA_Repository $EA_Repository) {
        $content = "";
        $this->smarty->assign("EA_Repository", $EA_Repository);
        $this->smarty->assign('actionurl', 'index.php?doc=db');
        $content .= $this->smarty->fetch('DatabaseDataForm.tpl');
        return $content;
    }

    public function getTablesInDatabase(array $tablesFromDatabase): string
    {
        $content = "";
        $this->smarty->assign('tabellen', $tablesFromDatabase);
        $this->smarty->assign('actionurl', 'index.php?doc=db');
        $content .= $this->smarty->fetch('DatabaseTablesTable.tpl');
        return $content;
    }

    public function getFormKonfiguration(array $fields): string
    {
        $content = "";
        $this->smarty->assign('actionurl', 'index.php?doc=konfiguration');
        $this->smarty->assign('settings', $fields);
        $content .= $this->smarty->fetch('ConfigurationForm.tpl');
        return $content;
    }

    public function getFormAltersklasse(EntityManager $entityManager, EA_AgeGroup $ageGroup, array $yearAgeGroupList): string
    {
        $content = "";
        $this->getStandardIncludes($entityManager, ["konfiguration" => true]);
        $this->smarty->assign('yearAgeGroupList', $yearAgeGroupList);
        $this->smarty->assign('ageGroup', $ageGroup);
        $this->smarty->assign('actionurl', 'index.php?doc=altersklassen');
        $content .= $this->smarty->fetch('AgeGroupFormCreateEdit.tpl');
        return $content;
    }

    public function getFormUrkundenelement(EA_CertificateElement $EA_U): string
    {
        $content = "";
        $this->smarty->assign('inhalt_selectvalues', EA_Certificate::INHALT_SELECTVALUES);
        $this->smarty->assign('schrifttyp_selectvalues', EA_Certificate::SCHRIFTTYP_SELECTVALUES);
        $this->smarty->assign('ausrichtung_selectvalues', EA_Certificate::AUSRICHTUNG_SELECTVALUES);
        $this->smarty->assign('schriftart_selectvalues', EA_Certificate::SCHRIFTART_SELECTVALUES);
        $this->smarty->assign('urkundenelement', $EA_U);
        $this->smarty->assign('actionurl', 'index.php?doc=urkundengenerator');
        $content .= $this->smarty->fetch('CertificateElementFormCreateEdit.tpl');
        return $content;
    }

    public function getFormStrecke(EA_Distance $EA_S): string
    {
        $content = "";
        $this->smarty->assign('strecke', $EA_S);
        $this->smarty->assign('actionurl', 'index.php?doc=strecken');
        $content .= $this->smarty->fetch('DistanceCreateEditForm.tpl');
        return $content;
    }

    public function getFormSpecialEvaluation(EntityManager $entityManager, EA_SpecialEvaluation $EA_SE): string
    {
        $content = "";
        $this->getStandardIncludes($entityManager, array("konfiguration" => true,"strecken"=>true,"altersklassen"=>true,"geschlechter"=>true));
        $this->smarty->assign('specialEvaluation', $EA_SE);
        $this->smarty->assign('actionurl', 'index.php?doc=specialevaluation');
        $content .= $this->smarty->fetch('SpecialEvaluationFormCreateEdit.tpl');
        return $content;
    }

    public function getFormUser(?EA_User $user=null): string
    {
        $content = "";
        $this->smarty->assign('user', $user);
        $this->smarty->assign('actionurl', 'index.php?doc=users');
        $content .= $this->smarty->fetch('UserForm.tpl');
        return $content;
    }

    public function getFormUserLogin(): string
    {
        $content = "";
        $this->smarty->assign('actionurl', 'index.php?doc=login');
        $content .= $this->smarty->fetch('UserFormLogin.tpl');
        return $content;
    }

    public function getFormMannschaftskategorie(EA_TeamCategory $EA_MK): string
    {
        $content = "";       
        $this->smarty->assign('mannschaftskategorie', $EA_MK);
        $this->smarty->assign('actionurl', 'index.php?doc=mannschaftskategorie');
        $content .= $this->smarty->fetch('TeamCategoryFormCreateEdit.tpl');
        return $content;
    }

    public function getFormSelectMannschaften(array $mannschaften): string
    {
        $content = "";
        $this->smarty->assign('mannschaften', $mannschaften);
        $this->smarty->assign('actionurl', 'index.php?doc=mannschaften');
        $content .= $this->smarty->fetch('TeamFormSelect.tpl');
        return $content;
    }

    public function getInfoSelbstanmeldung(EntityManager $entityManager, EA_Starter $EA_Starter): string
    {
        $content = "";
        $this->getStandardIncludes($entityManager, array("konfiguration" => true, "stati" => true));
        $this->smarty->assign('EA_Starter', $EA_Starter);
        $content .= $this->smarty->fetch('SelfCheckInContentInfoRegistration.tpl');
        return $content;
    }

    public function getInfoTeilnehmer(EntityManager $entityManager, EA_Starter $EA_Starter): string
    {
        $content = "";
        $this->getStandardIncludes($entityManager, array("konfiguration" => true, "stati" => true));
        $this->smarty->assign('EA_Starter', $EA_Starter);
        $content .= $this->smarty->fetch('ContentStarterInfoOverview.tpl');
        return $content;
    }

    public function getFormSelbstanmeldung(EntityManager $entityManager, EA_Configuration $konfiguration): string
    {
        $content = "";
        $this->getStandardIncludes($entityManager, array("konfiguration" => true, "mannschaften" => true, "strecken" => true, "startgruppen" => true, "geschlechter" => true, "stati" => true));

        if($konfiguration->getAltersklassen() === EA_Configuration::AGEGROUPMODUS_AGE){
            $this->smarty->assign('jahrgang', false);
        }else{
            $this->smarty->assign('jahrgang', true);
        }

        $this->smarty->assign('actionurl', 'index.php?doc=selbstanmeldung');
        $content .= $this->smarty->fetch('PublicSelfCheckInForm.tpl');
        return $content;
    }

    public function getFormKurzauskunft(): string
    {
        $this->smarty->assign('actionurl', 'index.php?doc=kurzauskunft');
        $content = $this->smarty->fetch('PublicSelfInfoForm.tpl');
        return $content;
    }

    public function getFormTeilnehmer(EntityManager $entityManager, EA_Starter $EA_Starter, EA_Configuration $configuration): string
    {
        $content = "";
        $this->getStandardIncludes($entityManager, array("konfiguration" => true, "mannschaften" => true, "strecken" => true, "startgruppen" => true, "geschlechter" => true, "stati" => true));
        $distanceRepository = new EA_DistanceRepository($entityManager);

        //if there ist only one distance, set standardvalue to save work
        $distanceList = $distanceRepository->loadList();
        $selectedDistanceId = count($distanceList) === 1 ? $distanceList[array_key_first($distanceList)]->getId() : null;
        
        if($configuration->getAltersklassen() === EA_Configuration::AGEGROUPMODUS_AGE){
            $this->smarty->assign('jahrgang', false);
        }else{
            $this->smarty->assign('jahrgang', true);
        }

        $val = (is_object($EA_Starter) && $EA_Starter->getId()) ? true : false;
        $this->smarty->assign('edit', $val);

        $val = (is_object($EA_Starter) && $EA_Starter->getStrecke()->getId()) ? $EA_Starter->getStrecke()->getId() : $selectedDistanceId;
        $this->smarty->assign('strecke', $val);

        $val = (is_object($EA_Starter) && $EA_Starter->getMannschaft()->getId()) ? $EA_Starter->getMannschaft()->getId() : null;
        $this->smarty->assign('mannschaft', $val);

        $val = (is_object($EA_Starter) && $EA_Starter->getVerein()->getVerein()) ? $EA_Starter->getVerein()->getVerein() : null;
        $this->smarty->assign('verein', $val);
        $val = (is_object($EA_Starter) && $EA_Starter->getVerein()->getId()) ? $EA_Starter->getVerein()->getId() : null;
        $this->smarty->assign('vereinid', $val);

        $this->smarty->assign('teilnehmer', $EA_Starter);
        $this->smarty->assign('actionurl', 'index.php?doc=teilnehmer');
        $content .= $this->smarty->fetch('StarterFormCreateEdit.tpl');
        return $content;
    }

    public function getFormMannschaft(EA_Team $mannschaft, array $mannschaftKategorieListForSelect): string
    {
        $content = "";
        $this->smarty->assign('mannschaftkategorieList', $mannschaftKategorieListForSelect);
        $this->smarty->assign('actionurl', 'index.php?doc=mannschaften');
        $this->smarty->assign('mannschaft', $mannschaft);
        $content .= $this->smarty->fetch('TeamFormCreateEdit.tpl');
        return $content;
    }

    public function getFormVerein(EA_Club $EA_V): string
    {
        $content = "";
        $this->smarty->assign('verein', $EA_V);
        $this->smarty->assign('actionurl', 'index.php?doc=vereine');
        $this->smarty->assign('editTeilnehmerUrl', 'index.php?doc=teilnehmer');
        $content .= $this->smarty->fetch('ClubFormCreateEdit.tpl');
        return $content;
    }

    public function getFormUnternehmen(EA_Company $EA_C): string
    {
        $content = "";
        $this->smarty->assign('unternehmen', $EA_C);
        $this->smarty->assign('actionurl', 'index.php?doc=unternehmen');
        $this->smarty->assign('editTeilnehmerUrl', 'index.php?doc=teilnehmer');
        $content .= $this->smarty->fetch('CompanyFormCreateEdit.tpl');
        return $content;
    }

    public function getFormStartzeiten(EntityManager $entityManager, array $nichtgestarteteteilnehmer, array $gestarteteteilnehmer): string
    {
        $content = "";
        $this->getStandardIncludes($entityManager, array("streckenV1" => true, "startgruppen" => true, "geschlechter" => true, "stati" => true,'altersklassen'=>true));
        $this->smarty->assign('nichtgestarteteteilnehmer', $nichtgestarteteteilnehmer);
        $this->smarty->assign('gestarteteteilnehmer', $gestarteteteilnehmer);
        $this->smarty->assign('datum', date('d.m.Y'));
        $this->smarty->assign('zeit', date('H:i:s'));
        $this->smarty->assign('actionurl', 'index.php?doc=startzeiten');
        $content .= $this->smarty->fetch('StartimeFormEdit.tpl');
        return $content;
    }

    public function getFormSelectTeilnehmer(): string
    {
        $content = "";
        $this->smarty->assign('actionurl', 'index.php?doc=buchungenstarter');
        $content .= $this->smarty->fetch('StarterFormSearch.tpl');
        return $content;
    }

    public function getFormBuchungenStarter(EntityManager $entityManager, EA_Starter $teilnehmer, array $statisticsDaten): string
    {   
        
        //important to call here. When it is called in the assign method, roundtimes are not calculated
        $hitList = $teilnehmer->getImpulseListGueltige(true);
        $content = "";
        $content .= $this->getInfoTeilnehmer($entityManager, $teilnehmer);
        // Buchungstabelle
        $this->smarty->assign('impulse', $hitList);
        $this->smarty->assign('teilnehmerId', $teilnehmer->getId());
        $this->smarty->assign('actionurl', 'index.php?doc=buchungenstarter');
        $this->getStandardIncludes($entityManager, array("konfiguration" => true));
        $content .= $this->smarty->fetch('StarterFormHitOverview.tpl');

        // Diagramm
        $this->getStandardIncludes($entityManager, array("konfiguration" => true));
        $this->smarty->assign('daten', $statisticsDaten);
        $content .= $this->smarty->fetch('StatisticsPageContentLine.tpl');
        return $content;
    }

    public function getFormManuelleEingabe(EntityManager $entityManager, array $streckeListForSelect): string
    {
        $content = "";
        $this->getStandardIncludes($entityManager, array("konfiguration" => true, "strecken" => true));
        $this->smarty->assign('strecken', $streckeListForSelect);
        $this->smarty->assign('actionurl', 'index.php?doc=manuelleeingabe');
        $content .= $this->smarty->fetch('HitManuelInsertForm.tpl');
        return $content;
    }

    public function getFormTransponderrueckgabe(array $teilnehmer): string
    {
        $content = "";
        $this->smarty->assign('actionurl', 'index.php?doc=transponderrueckgabe');
        $this->smarty->assign('teilnehmer', $teilnehmer);
        $content .= $this->smarty->fetch('RfidReturnForm.tpl');
        return $content;
    }

    public function getFormFehlbuchungen(array $fehlbuchungen): string
    {
        $content = "";
        $this->smarty->assign('actionurl', 'index.php?doc=fehlbuchungen');
        $this->smarty->assign('fehlbuchungen', $fehlbuchungen);
        $content .= $this->smarty->fetch('HitFalseEntryTable.tpl');
        return $content;
    }

    public function getFormImport(): string
    {
        $content = "";
        $this->smarty->assign('actionurl', 'index.php?doc=import');
        $content .= $this->smarty->fetch('ImportForm.tpl');
        return $content;
    }

    public function getFormBuchungsuebersicht(EntityManager $entityManager, array $hitList): string
    {
        $content = "";
        $this->getStandardIncludes($entityManager, array("konfiguration" => true));
        $this->smarty->assign('actionurl', 'index.php?doc=buchungsuebersicht');
        $this->smarty->assign('hitList', $hitList);
        $content .= $this->smarty->fetch('HitTable.tpl');
        return $content;
    }

    public function getFormTeilnehmeruebersicht(EntityManager $entityManager, array $teilnehmer): string
    {
        $content = "";
        $this->getStandardIncludes($entityManager, array("konfiguration" => true));
        $this->smarty->assign('actionurl', 'index.php?doc=teilnehmeruebersicht');
        $this->smarty->assign('teilnehmer', $teilnehmer);
        $content .= $this->smarty->fetch('StarterTable.tpl');
        return $content;
    }

    public function getFormStatusVerwalten(EntityManager $entityManager, array $teilnehmer): string
    {
        $content = "";
        $this->getStandardIncludes($entityManager, array("konfiguration" => true));
        $this->smarty->assign('actionurl', 'index.php?doc=teilnehmeruebersicht');
        $this->smarty->assign('teilnehmer', $teilnehmer);
        $content .= $this->smarty->fetch('StarterStatusAdminForm.tpl');
        return $content;
    }

    public function getFormVereinsfusion(array $vereinList): string
    {
        $content = "";
        $this->smarty->assign('vereine', $vereinList);
        $this->smarty->assign('actionurl', 'index.php?doc=vereine&action=vereinsfusion');
        $content .= $this->smarty->fetch('ClubFusionForm.tpl');
        return $content;
    }

    public function getFormUnternehmensfusion(array $unternehmenList): string
    {
        $content = "";
        $this->smarty->assign('unternehmen', $unternehmenList);
        $this->smarty->assign('actionurl', 'index.php?doc=unternehmen&action=unternehmensfusion');
        $content .= $this->smarty->fetch('CompanyFusionForm.tpl');
        return $content;
    }

    public function getContentSpezialabfragen(
        $teilnehmer,
        $globaleVeranstaltungsdaten,
        $vereineLeistung,
        $statiVerteilung,
        $medaillenspiegel,
        EA_Starter $besterTeilnehmer,
        array $gemeldeteTeilnehmer,
        $StreckenTeilnehmerVerteilung,
        $StreckenAltersklassenTeilnehmerVerteilung,
        EA_StarterRepository $EA_StarterRepository,
        EntityManager $entityManager): string
    {
        $content = "";
        $this->getStandardIncludes($entityManager, array("konfiguration"=>true,"streckenV1" => true, "geschlechterKurz" => true, "altersklassen" => true, "vereine" => true));

        $this->smarty->assign('vereineLeistung',$vereineLeistung);
        $this->smarty->assign('aeltesterTeilnehmerMann', $teilnehmer['MMax']);
        $this->smarty->assign('juengsterTeilnehmerMann', $teilnehmer['MMin']);
        $this->smarty->assign('aeltesterTeilnehmerFrau',$teilnehmer['WMax']);
        $this->smarty->assign('juengsterTeilnehmerFrau', $teilnehmer['WMin']);
        $this->smarty->assign('gemeldeteTeilnehmer', count($gemeldeteTeilnehmer));
        $this->smarty->assign('teilnehmerRepository', $EA_StarterRepository);
        $this->smarty->assign('EA_H', $this->EA_H);
        $this->smarty->assign('statiVerteilung', $statiVerteilung);
        $this->smarty->assign('stati', EA_Starter::STATUS_LIST);
        $this->smarty->assign('medaillenspiegel', $medaillenspiegel);
        $this->smarty->assign('besterTeilnehmer', $besterTeilnehmer);
        $this->smarty->assign('StreckenTeilnehmerVerteilung', $StreckenTeilnehmerVerteilung);
        $this->smarty->assign('StreckenAltersklassenTeilnehmerVerteilung', $StreckenAltersklassenTeilnehmerVerteilung);
        $this->smarty->assign('meter', $globaleVeranstaltungsdaten['erreichteMeter']  );
        $this->smarty->assign('geld', $globaleVeranstaltungsdaten['erreichtesGeld']);
        $this->smarty->assign('anzahlStreckenart', $globaleVeranstaltungsdaten['anzahlStreckenart'] );
        $this->smarty->assign('meterProTeilnehmer', $globaleVeranstaltungsdaten['erreichteMeterProTeilnehmer'] );               

        $this->smarty->registerPlugin('modifier', 'is_object', 'is_object');
        $content .= $this->smarty->fetch('SpecialInformationPage.tpl');
        return $content;
    }

    public function getContentPresse(): string
    {
        $content = "";
        $content .= $this->smarty->fetch('ContentPresse.tpl');
        return $content;
    }

    public function getContentErgebnisse(
        $filter, 
        $ergebnisse, 
        $StreckenAltersklassenTeilnehmerVerteilung, 
        $AltersklassenTeilnehmerVerteilung, 
        ?EA_SpecialEvaluation $specialEvaluation,
        EA_SpecialEvaluationRepository $EA_SpecialEvaluationRepository,
        EntityManager $entityManager): string
    {
        $content = "";
        $this->getStandardIncludes($entityManager, array("streckenV1" => true, "konfiguration" => true, "geschlechterKurz" => true, "altersklassen" => true, "startgruppen" => true));
        $this->smarty->assign('ausgabetyp', "HTML");
        $this->smarty->assign('filter', $filter);
        $this->smarty->assign('specialEvaluation', $specialEvaluation);
        $this->smarty->assign('specialEvaluationList', $EA_SpecialEvaluationRepository->getListForSelectField() );
        $this->smarty->assign('AltersklassenTeilnehmerVerteilung', $AltersklassenTeilnehmerVerteilung);
        $this->smarty->assign('StreckenAltersklassenTeilnehmerVerteilung', $StreckenAltersklassenTeilnehmerVerteilung);
        $this->smarty->assign('actionurl', 'index.php?doc=ergebnisse');
        $this->smarty->assign('pdfurl', 'service.php?doc=ergebnisse');
        $this->smarty->assign('ergebnisse', $ergebnisse);
        $content .= $this->smarty->fetch('ResultPage.tpl');
        return $content;
    }

    public function getContentUrkunden(EntityManager $entityManager, $StreckenAltersklassenTeilnehmerVerteilung,$AltersklassenTeilnehmerVerteilung): string
    {
        $content = "";
        $this->getStandardIncludes($entityManager, array("geschlechterKurz" => true, "altersklassen" => true, "streckenV1" => true, "startgruppen" => true));
        $this->smarty->assign('AltersklassenTeilnehmerVerteilung', $AltersklassenTeilnehmerVerteilung);
        $this->smarty->assign('StreckenAltersklassenTeilnehmerVerteilung', $StreckenAltersklassenTeilnehmerVerteilung);
        $this->smarty->assign('actionurl', 'index.php?doc=urkunden');
        $this->smarty->assign('pdfurl', 'service.php?doc=urkunden');
        $content .= $this->smarty->fetch('CertificatePage.tpl');
        return $content;
    }

    public function getContentMeldelisten(EntityManager $entityManager, $StreckenAltersklassenTeilnehmerVerteilung,$AltersklassenTeilnehmerVerteilung): string
    {
        $content = "";
        $this->getStandardIncludes($entityManager, array("streckenV1" => true, "geschlechterKurz" => true, "altersklassen" => true, "startgruppen" => true));
        $this->smarty->assign('AltersklassenTeilnehmerVerteilung', $AltersklassenTeilnehmerVerteilung);
        $this->smarty->assign('StreckenAltersklassenTeilnehmerVerteilung', $StreckenAltersklassenTeilnehmerVerteilung);
        $this->smarty->assign('actionurl', 'index.php?doc=meldelisten');
        $this->smarty->assign('pdfurl', 'service.php?doc=meldelisten');
        $content .= $this->smarty->fetch('ReportListsPage.tpl');
        return $content;
    }

    public function getContentStatistik(EntityManager $entityManager, string $template="", array $daten=[], string $title="",string $explanation=""): string
    {
        $content = "";
        $this->getStandardIncludes($entityManager, array("konfiguration" => true));
        $this->smarty->assign('actionurl', 'index.php?doc=statistik');
        $this->smarty->assign('EA_H', $this->EA_H);
        
        $content .= $this->smarty->fetch('StatisticsFormSelect.tpl');
        
        if($template !== ""){
            $this->smarty->assign('daten', $daten);
            $this->smarty->assign('title', $title);
            $this->smarty->assign('explanation', $explanation);
            $content .= $this->smarty->fetch($template);         
        }
        return $content;
    }

    public function getFormSpecialDaten(EntityManager $entityManager, array $globaleLeistungsdaten): string
    {
        $content = "";
        $this->getStandardIncludes($entityManager, array("konfiguration" => true));
        $this->smarty->assign('gestarteteTeilnehmer', $globaleLeistungsdaten['gestarteteTeilnehmer']);
        $this->smarty->assign('streckenart', $globaleLeistungsdaten['streckenart'] );
        $this->smarty->assign('meter', $globaleLeistungsdaten['erreichteMeter'] );
        $this->smarty->assign('restmeter', $globaleLeistungsdaten['restmeter'] );
        $this->smarty->assign('bahnen', $globaleLeistungsdaten['anzahlStreckenart'] );
        $this->smarty->assign('geld', $globaleLeistungsdaten['erreichtesGeld'] );
        $this->smarty->assign('restgeld', $globaleLeistungsdaten['restgeld'] );
        $this->smarty->assign('prozent', $globaleLeistungsdaten['erreichteProzent'] );
        $this->smarty->assign('spendensumme', $globaleLeistungsdaten['spendensumme'] );

        $this->smarty->assign('actionurl', 'index.php?doc=teilnehmer');
        $this->smarty->registerPlugin('modifier', 'date', 'date');
        $content .= $this->smarty->fetch('ContentImportantDataOverwiev.tpl');
        return $content;
    }

    public function getFormSpecialAlarm(
        ?array $teilnehmerWrongStreckeList,
        ?array $teilnehmerWrongAltersklasseList,
        ?array $teilnehmerWrongTransponderList,
        ?array $teilnehmerWrongStartzeit1List,
        ?array $teilnehmerWrongStartzeit2List
    ): string
    {
        $this->smarty->assign('teilnehmerWrongStreckeList', $teilnehmerWrongStreckeList);
        $this->smarty->assign('teilnehmerWrongAltersklasseList', $teilnehmerWrongAltersklasseList);
        $this->smarty->assign('teilnehmerWrongTransponderList', $teilnehmerWrongTransponderList);
        $this->smarty->assign('teilnehmerWrongStartzeit1List', $teilnehmerWrongStartzeit1List);
        $this->smarty->assign('teilnehmerWrongStartzeit2List', $teilnehmerWrongStartzeit2List);

        $this->smarty->assign('actionurl', 'index.php?doc=teilnehmer');
        return $this->smarty->fetch('IndexPageContentAlarm.tpl');
    }

    public function getFormSpecialNachrichten($nachrichten): string
    {
        $content = "";
        $this->smarty->assign('nachrichten', $nachrichten);
        $content .= $this->smarty->fetch('IndexPageContentSpecialNews.tpl');
        return $content;
    }

    public function getFormSpecialLogin(): string
    {
        $content = "";
        $content .= $this->smarty->fetch('ContentLoginForm.tpl');
        return $content;
    }

}
