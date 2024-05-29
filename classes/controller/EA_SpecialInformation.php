<?php
namespace CharitySwimRun\classes\controller;

use Doctrine\ORM\EntityManager;
use CharitySwimRun\classes\model\EA_Starter;
use CharitySwimRun\classes\helper\EA_PlacementHelper;
use CharitySwimRun\classes\model\EA_Message;

class EA_SpecialInformation extends EA_Controller
{
    private EA_PlacementHelper $EA_PlacementHelper;

    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager);
        $this->EA_PlacementHelper = new EA_PlacementHelper($entityManager);

    }

    public function getPageSpezialabfragen(): string
    {     
        $this->EA_StarterRepository->berechneStati();
        $teilnehmerList = $this->EA_StarterRepository->loadList(null,null,null,null,null,null,null,"impulseCache","DESC");
        if(count($teilnehmerList) === 0){
            $this->EA_Messages->addMessage("Veranstaltung hat noch keine Daten",123237377333,EA_Message::MESSAGE_WARNING);
            return "";
        }
        $this->EA_PlacementHelper->berechnePlatzierung($teilnehmerList);
        
        $content = "";
        $globaleVeranstaltungsdaten = $this->EA_HitRepository->getGlobaleVeranstaltungsleistungsdaten(count($teilnehmerList),$this->konfiguration);
        $besterTeilnehmer = $teilnehmerList[array_key_first($teilnehmerList)]; //Den TN mit den meisten Buchungen auslesen
        $medaillenspiegel = $this->EA_StarterRepository->loadMedaillenspiegel($teilnehmerList);
        $statiVerteilung = $this->EA_StarterRepository->loadStatiVerteilung();
        $vereineLeistung = $this->EA_ClubRepository->loadList("verein");
        $StreckenTeilnehmerVerteilung = $this->EA_StarterRepository->loadStreckenTeilnehmerVerteilung();
        $StreckenAltersklassenTeilnehmerVerteilung = $this->EA_StarterRepository->loadStreckenAltersklassenTeilnehmerVerteilung();

        $teilnehmer['MMin'] = $this->EA_StarterRepository->loadOldestYoungstByGender(EA_Starter::GESCHLECHT_M, "DESC");
        $teilnehmer['MMax'] = $this->EA_StarterRepository->loadOldestYoungstByGender(EA_Starter::GESCHLECHT_M, "ASC");
        $teilnehmer['WMin'] = $this->EA_StarterRepository->loadOldestYoungstByGender(EA_Starter::GESCHLECHT_W, "DESC");
        $teilnehmer['WMax'] = $this->EA_StarterRepository->loadOldestYoungstByGender(EA_Starter::GESCHLECHT_W, "ASC");

        $content .= $this->EA_FR->getContentSpezialabfragen($teilnehmer, $globaleVeranstaltungsdaten, $vereineLeistung, $statiVerteilung, $medaillenspiegel, $besterTeilnehmer, $teilnehmerList, $StreckenTeilnehmerVerteilung, $StreckenAltersklassenTeilnehmerVerteilung, $this->EA_StarterRepository,$this->entityManager);
        return $content;
    }

}