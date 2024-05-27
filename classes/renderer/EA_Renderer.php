<?php

namespace CharitySwimRun\classes\renderer;

require_once (ROOT_PATH.'/vendor/autoload.php');

use CharitySwimRun\classes\model\EA_Konfiguration;
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
        return $this->smarty->fetch('DisplayTabelleUserList.tpl');
    }

    public function renderTabelleStrecken(array $streckeList): string
    {
        $this->smarty->assign('link', "index.php?doc=strecken");
        $this->smarty->assign('streckeList', $streckeList);
        return $this->smarty->fetch('DisplayTabelleStrecken.tpl');
    }

    public function renderTabelleSpecialEvaluation(array $specialEvaluationList): string
    {
        $this->smarty->assign('link', "index.php?doc=specialevaluation");
        $this->smarty->assign('specialEvaluationList', $specialEvaluationList);
        return $this->smarty->fetch('DisplayTabelleSpecialEvaluation.tpl');
    }

    public function renderTabelleMannschaftskategorien(array $mannschaftskategorien): string
    {
        $this->smarty->assign('link', "index.php?doc=mannschaftskategorie");
        $this->smarty->assign('mannschaftskategorien', $mannschaftskategorien);
        return $this->smarty->fetch('DisplayTabelleMannschaftskategorien.tpl');
    }

    public function renderTabelleAltersklassen(array $altersklasseList, EA_Konfiguration $konfig): string
    {
        $this->smarty->assign('konfiguration', $konfig);
        $this->smarty->assign('jahrgang', $konfig->getAltersklassen());
        $this->smarty->assign('link', "index.php?doc=altersklassen");
        $this->smarty->assign('altersklassen', $altersklasseList);
        return $this->smarty->fetch('DisplayTabelleAltersklassen.tpl');
    }

    public function renderTabelleUrkundenelemente(array $urkundenelemente): string
    {
        $this->smarty->assign('link', "index.php?doc=urkundengenerator");
        $this->smarty->assign('urkundenelemente', $urkundenelemente);
        return $this->smarty->fetch('DisplayTabelleUrkundenelemente.tpl');
    }

    public function renderUrkundengeneratorJavascript(array $urkundenelemente): string
    {
        $this->smarty->assign('link', "index.php?doc=urkundengenerator");
        $this->smarty->assign('HTMLSchrifttyp', array(" " => "font-weight:normal", "B" => "font-weight:bold", "U" => "text-decoration:underline", "I" => "font-style:style:italic"));
        $this->smarty->assign('HTMLTextAusrichtung', array("C" => "center", "L" => "left", "R" => "right"));
        $this->smarty->assign('urkundenelemente', $urkundenelemente);
        return $this->smarty->fetch('DisplayUrkundengeneratorJavascript.tpl');
    }

    public function renderTabelleVereine(array $vereine): string
    {
        $this->smarty->assign('link', "index.php?doc=vereine");
        $this->smarty->assign('vereine', $vereine);
        return $this->smarty->fetch('DisplayTabelleVereine.tpl');
    }

    public function renderIndex($ips): string
    {
        $this->smarty->assign('ips', $ips);
        $this->smarty->assign('serverzeit', date("d.m.Y H:i:s"));
        $this->smarty->assign('serverzeitzone', date_default_timezone_get());
        return $this->smarty->fetch('DisplayIndexPage.tpl');
    }
    public function getDashboardExtrapolration(array $stundenMeterList,int $meterProMinute,int $zielMeterFuerSpendensumme): string
    {
        $this->smarty->assign('stundenMeterList', $stundenMeterList);
        $this->smarty->assign('meterProMinute', $meterProMinute);
        $this->smarty->assign('zielMeterFuerSpendensumme', $zielMeterFuerSpendensumme);
        return $this->smarty->fetch('DisplayDashboardExtrapolration.tpl');
    }

}