<?php
namespace CharitySwimRun\classes\controller;

use CharitySwimRun\classes\helper\EA_StatistikHelper;

use CharitySwimRun\classes\model\EA_Repository;


class EA_StatistikController extends EA_Controller
{
    private EA_StatistikHelper $EA_StatistikHelper;

    public function __construct( EA_Repository $EA_Repository)
    {
        parent::__construct($EA_Repository->getEntityManager());
        $this->EA_StatistikHelper = new EA_StatistikHelper($EA_Repository,$this->konfiguration);
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
                    $daten = $this->EA_StatistikHelper->loadStatistikData("TNproLeser");
                    $template = 'StatisticsBar.tpl';
                    $title = 'Teilnehmer pro Leser';
                    $explanation = 'Dieser Wert gibt aus, wieviele unterschiedliche Startnummern in den letzten 3 Minuten an einem Leser gebucht haben. Es ist also nur eine Schätzung';
                    break;
                case "BpH":
                    $daten = $this->EA_StatistikHelper->loadStatistikData("BuchungenProStunde");
                    $template = 'StatisticsLine.tpl';
                    $title = $this->konfiguration->getStreckenart().' pro Stunde';
                    break;
                case "BpL":
                    $daten = $this->EA_StatistikHelper->loadStatistikData("BuchungenProLeser");
                    $template = 'StatisticsBar.tpl';
                    $title = 'Buchungen pro Leser';
                    break;
                case "BpHuLeser":
                    $daten = $this->EA_StatistikHelper->loadStatistikData("BuchungenProStundeUndLeser");
                    $template = 'StatisticsLine.tpl';
                    $title = $this->konfiguration->getStreckenart().' pro Leser und Stunde';
                    break;
                case "SpH":
                    $daten = $this->EA_StatistikHelper->loadStatistikData("AktiveTNproStunde");
                    $template = 'StatisticsLine.tpl';
                    $title = 'Aktive Teilnehmer pro Stunde';
                    break;
                case "Performance":
                    $daten = $this->EA_StatistikHelper->loadStatistikData("Performance");
                    $template = 'StatisticsLine.tpl';
                    $title = 'Aktuelle Performance basierend auf den letzten 15min';
                    break;
                case "TNpH":
                    $daten = $this->EA_StatistikHelper->loadStatistikData("GestarteteTNproStunde");
                    $template = 'StatisticsLine.tpl';
                    $title = 'Gestarte Teilnehmer pro Stunde';
                    break;
            }
        }

        $content .= $this->EA_FR->getContentStatistik($this->entityManager, $template, $daten, $title, $explanation);
        return $content;
    }
}