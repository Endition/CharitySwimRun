<?php

namespace CharitySwimRun\classes\renderer;

require_once (ROOT_PATH.'/vendor/autoload.php');

use CharitySwimRun\classes\model\EA_Configuration;
use Smarty\Smarty;

class EA_Renderer extends EA_AbstractRenderer
{


    private string $ds;
    private Smarty $smarty;

    public function __construct()
    {
        parent::__construct();
        $this->ds = DIRECTORY_SEPARATOR;
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir(dirname(__FILE__) . "" . $this->ds . "templates" . $this->ds)
            ->setCompileDir(dirname(__FILE__) . "" . $this->ds . "templates_c" . $this->ds)
            ->setCacheDir(dirname(__FILE__) . "" . $this->ds . "cache" . $this->ds)
            ->setConfigDir(dirname(__FILE__) . "" . $this->ds . "configs" . $this->ds);
        $this->smarty->debugging = false;
    }

    public function deleteSmartyCache()
    {
        // alle Cache-Dateien löschen
        $this->smarty->clearAllCache();
    }

    public function renderTabelleUser(array $userList): string
    {
        $this->smarty->assign('link', "index.php?doc=users");
        $this->smarty->assign('userList', $userList);
        return $this->smarty->fetch('UserTable.tpl');
    }

    public function renderTabelleStrecken(array $streckeList): string
    {
        $this->smarty->assign('link', "index.php?doc=strecken");
        $this->smarty->assign('streckeList', $streckeList);
        return $this->smarty->fetch('DistanceTable.tpl');
    }

    public function renderTabelleSpecialEvaluation(array $specialEvaluationList): string
    {
        $this->smarty->assign('link', "index.php?doc=specialevaluation");
        $this->smarty->assign('specialEvaluationList', $specialEvaluationList);
        return $this->smarty->fetch('SpecialEvaluationTable.tpl');
    }

    public function renderTabelleMannschaftskategorien(array $mannschaftskategorien): string
    {
        $this->smarty->assign('link', "index.php?doc=mannschaftskategorie");
        $this->smarty->assign('mannschaftskategorien', $mannschaftskategorien);
        return $this->smarty->fetch('TeamCategoryTable.tpl');
    }

    public function renderTabelleAltersklassen(array $altersklasseList, EA_Configuration $konfig): string
    {
        $this->smarty->assign('konfiguration', $konfig);
        $this->smarty->assign('jahrgang', $konfig->getAltersklassen());
        $this->smarty->assign('link', "index.php?doc=altersklassen");
        $this->smarty->assign('altersklassen', $altersklasseList);
        return $this->smarty->fetch('AgeGroupTable.tpl');
    }

    public function renderTabelleUrkundenelemente(array $urkundenelemente): string
    {
        $this->smarty->assign('link', "index.php?doc=urkundengenerator");
        $this->smarty->assign('urkundenelemente', $urkundenelemente);
        return $this->smarty->fetch('CertificateElementTable.tpl');
    }

    public function renderUrkundengeneratorJavascript(array $urkundenelemente): string
    {
        $this->smarty->assign('link', "index.php?doc=urkundengenerator");
        $this->smarty->assign('HTMLSchrifttyp', array(" " => "font-weight:normal", "B" => "font-weight:bold", "U" => "text-decoration:underline", "I" => "font-style:style:italic"));
        $this->smarty->assign('HTMLTextAusrichtung', array("C" => "center", "L" => "left", "R" => "right"));
        $this->smarty->assign('urkundenelemente', $urkundenelemente);
        return $this->smarty->fetch('CertificateGeneratorJavascript.tpl');
    }

    public function renderTabelleVereine(array $vereine): string
    {
        $this->smarty->assign('link', "index.php?doc=vereine");
        $this->smarty->assign('vereine', $vereine);
        return $this->smarty->fetch('ClubTable.tpl');
    }

    public function renderTabelleUnternehmen(array $unternehmen): string
    {
        $this->smarty->assign('link', "index.php?doc=unternehmen");
        $this->smarty->assign('unternehmen', $unternehmen);
        return $this->smarty->fetch('CompanyTable.tpl');
    }

    public function renderIndex(array $ipList): string
    {
        $this->smarty->assign('ipList', $ipList);
        $this->smarty->assign('serverzeit', date("d.m.Y H:i:s"));
        $this->smarty->assign('serverzeitzone', date_default_timezone_get());
        $this->smarty->assign('httphost', $_SERVER['HTTP_HOST']);
        return $this->smarty->fetch('IndexPage.tpl');
    }
    public function getDashboardExtrapolration(array $stundenMeterList,int $meterProMinute,int $zielMeterFuerSpendensumme): string
    {
        $this->smarty->assign('stundenMeterList', $stundenMeterList);
        $this->smarty->assign('meterProMinute', $meterProMinute);
        $this->smarty->assign('zielMeterFuerSpendensumme', $zielMeterFuerSpendensumme);
        return $this->smarty->fetch('DashboardContentExtrapolaration.tpl');
    }

}