<?php
namespace CharitySwimRun\classes\controller;

use CharitySwimRun\classes\helper\EA_StatisticsHelper;

use CharitySwimRun\classes\model\EA_Repository;


class EA_StatsticsController extends EA_Controller
{
    private EA_StatisticsHelper $EA_StatisticsHelper;

    public function __construct( EA_Repository $EA_Repository)
    {
        parent::__construct($EA_Repository->getEntityManager());
        $this->EA_StatisticsHelper = new EA_StatisticsHelper($EA_Repository,$this->konfiguration);
    }
    public function getPageStatistik(): string
    {
        $template = "";
        $daten = [];
        $content = "";
        $title = "";
        $explanation = "";
        if (isset($_POST['sendStatstikViewData']) && isset($_POST['statistikauswahl'])) {
            switch ($_POST['statistikauswahl']) {
                case "TNpLeser" :
                    $daten = $this->EA_StatisticsHelper->loadStatistikData("TNproLeser");
                    $template = 'StatisticsPageContentBar.tpl';
                    $title = 'Teilnehmer pro Leser';
                    $explanation = 'Dieser Wert gibt aus, wieviele unterschiedliche Startnummern in den letzten 3 Minuten an einem Leser gebucht haben. Es ist also nur eine Schätzung';
                    break;
                case "BpH":
                    $daten = $this->EA_StatisticsHelper->loadStatistikData("BuchungenProStunde");
                    $template = 'StatisticsPageContentLine.tpl';
                    $title = $this->konfiguration->getStreckenart().' pro Stunde';
                    break;
                case "BpL":
                    $daten = $this->EA_StatisticsHelper->loadStatistikData("BuchungenProLeser");
                    $template = 'StatisticsPageContentBar.tpl';
                    $title = 'Buchungen pro Leser';
                    break;
                case "BpHuLeser":
                    $daten = $this->EA_StatisticsHelper->loadStatistikData("BuchungenProStundeUndLeser");
                    $template = 'StatisticsPageContentLine.tpl';
                    $title = $this->konfiguration->getStreckenart().' pro Leser und Stunde';
                    break;
                case "SpH":
                    $daten = $this->EA_StatisticsHelper->loadStatistikData("AktiveTNproStunde");
                    $template = 'StatisticsPageContentLine.tpl';
                    $title = 'Aktive Teilnehmer pro Stunde';
                    break;
                case "Performance":
                    $daten = $this->EA_StatisticsHelper->loadStatistikData("Performance");
                    $template = 'StatisticsPageContentLine.tpl';
                    $title = 'Aktuelle Performance basierend auf den letzten 15min';
                    break;
                case "TNpH":
                    $daten = $this->EA_StatisticsHelper->loadStatistikData("GestarteteTNproStunde");
                    $template = 'StatisticsPageContentLine.tpl';
                    $title = 'Gestarte Teilnehmer pro Stunde';
                    break;
            }
        }

        $content .= $this->EA_FR->getContentStatistik($this->entityManager, $template, $daten, $title, $explanation);
        return $content;
    }
}