<?php
namespace CharitySwimRun\classes\controller;

use Doctrine\ORM\EntityManager;
use CharitySwimRun\classes\model\EA_Teilnehmer;
use CharitySwimRun\classes\helper\EA_PlatzierungBerechner;

class EA_SpezialabfragenController extends EA_Controller
{
    private EA_PlatzierungBerechner $EA_PlatzierungBerechner;

    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager);
        $this->EA_PlatzierungBerechner = new EA_PlatzierungBerechner($entityManager);

    }

    public function getPageSpezialabfragen(): string
    {     
        $this->EA_TeilnehmerRepository->berechneStati();
        $teilnehmerList = $this->EA_TeilnehmerRepository->loadList(null,null,null,null,null,null,null,"impulseCache","DESC");
        $this->EA_PlatzierungBerechner->berechnePlatzierung($teilnehmerList);
        
        $content = "";
        $globaleVeranstaltungsdaten = $this->EA_ImpulsRepository->getGlobaleVeranstaltungsleistungsdaten(count($teilnehmerList),$this->konfiguration);
        $gemeldeteTeilnemer = count($teilnehmerList);
        $besterTeilnehmer = $teilnehmerList[array_key_first($teilnehmerList)]; //Den TN mit den meisten Buchungen auslesen
        $medaillenspiegel = $this->EA_TeilnehmerRepository->loadMedaillenspiegel($teilnehmerList);
        $statiVerteilung = $this->EA_TeilnehmerRepository->loadStatiVerteilung();
        $vereineLeistung = $this->EA_VereinRepository->loadList("verein");
        $StreckenTeilnehmerVerteilung = $this->EA_TeilnehmerRepository->loadStreckenTeilnehmerVerteilung();
        $StreckenAltersklassenTeilnehmerVerteilung = $this->EA_TeilnehmerRepository->loadStreckenAltersklassenTeilnehmerVerteilung();

        $teilnehmer['MMin'] = $this->EA_TeilnehmerRepository->loadOldestYoungstByGender(EA_Teilnehmer::GESCHLECHT_M, "DESC");
        $teilnehmer['MMax'] = $this->EA_TeilnehmerRepository->loadOldestYoungstByGender(EA_Teilnehmer::GESCHLECHT_M, "ASC");
        $teilnehmer['WMin'] = $this->EA_TeilnehmerRepository->loadOldestYoungstByGender(EA_Teilnehmer::GESCHLECHT_W, "DESC");
        $teilnehmer['WMax'] = $this->EA_TeilnehmerRepository->loadOldestYoungstByGender(EA_Teilnehmer::GESCHLECHT_W, "ASC");

        $content .= $this->EA_FR->getContentSpezialabfragen($teilnehmer, $globaleVeranstaltungsdaten, $vereineLeistung, $statiVerteilung, $medaillenspiegel, $besterTeilnehmer, $gemeldeteTeilnemer, $StreckenTeilnehmerVerteilung, $StreckenAltersklassenTeilnehmerVerteilung, $this->EA_TeilnehmerRepository,$this->entityManager);
        return $content;
    }

}