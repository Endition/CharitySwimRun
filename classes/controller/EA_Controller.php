<?php
namespace CharitySwimRun\classes\controller;

use Doctrine\ORM\EntityManager;

use CharitySwimRun\classes\model\EA_TeilnehmerRepository;
use CharitySwimRun\classes\model\EA_KonfigurationRepository;
use CharitySwimRun\classes\renderer\EA_FormRenderer;
use CharitySwimRun\classes\renderer\EA_Renderer;
use CharitySwimRun\classes\model\EA_Messages;
use CharitySwimRun\classes\model\EA_AltersklasseRepository;
use CharitySwimRun\classes\model\EA_ImpulsRepository;
use CharitySwimRun\classes\model\EA_Konfiguration;
use CharitySwimRun\classes\model\EA_ClubRepository;
use CharitySwimRun\classes\model\EA_MannschaftRepository;
use CharitySwimRun\classes\model\EA_SpecialEvaluationRepository;
use CharitySwimRun\classes\model\EA_StreckeRepository;
use CharitySwimRun\classes\model\EA_MannschaftskategorieRepository;
use CharitySwimRun\classes\model\EA_UserRepository;
use CharitySwimRun\classes\model\EA_UrkundenelementRepository;

class EA_Controller
{
    protected ?EntityManager $entityManager;

    protected EA_Renderer $EA_R;
    protected EA_FormRenderer $EA_FR;
    protected EA_AltersklasseRepository $EA_AltersklasseRepository;
    protected EA_TeilnehmerRepository $EA_TeilnehmerRepository;
    protected EA_KonfigurationRepository $EA_KonfigurationRepository;
    protected EA_ImpulsRepository $EA_ImpulsRepository;
    protected EA_ClubRepository $vereinRepository;
    protected EA_MannschaftRepository $EA_MannschaftRepository;
    protected EA_SpecialEvaluationRepository $EA_SpecialEvaluationRepository;
    protected EA_ClubRepository $EA_ClubRepository;
    protected EA_StreckeRepository $EA_StreckeRepository;
    protected EA_MannschaftskategorieRepository $EA_MannschaftskategorieRepository;
    protected EA_UserRepository $EA_UserRepository;
    protected EA_UrkundenelementRepository $EA_UrkundenelementRepository;  
  

    protected EA_Messages $EA_Messages;
    protected ?EA_Konfiguration $konfiguration;

    
    public function __construct(EntityManager $entityManager){
        $this->entityManager = $entityManager;
        
        $this->EA_FR = new EA_FormRenderer();
        $this->EA_R = new EA_Renderer();
        $this->EA_Messages = new EA_Messages();
        $this->EA_UserRepository = new EA_UserRepository($entityManager);
        $this->EA_AltersklasseRepository = new EA_AltersklasseRepository($entityManager);
        $this->EA_TeilnehmerRepository = new EA_TeilnehmerRepository($entityManager);
        $this->EA_KonfigurationRepository = new EA_KonfigurationRepository($entityManager);
        $this->EA_ImpulsRepository = new EA_ImpulsRepository($entityManager);
        $this->EA_MannschaftRepository = new EA_MannschaftRepository($entityManager);
        $this->EA_SpecialEvaluationRepository = new EA_SpecialEvaluationRepository($entityManager);
        $this->EA_ClubRepository = new EA_ClubRepository($entityManager);
        $this->EA_StreckeRepository = new EA_StreckeRepository($entityManager);
        $this->EA_MannschaftskategorieRepository = new EA_MannschaftskategorieRepository($entityManager);
        $this->EA_UrkundenelementRepository = new EA_UrkundenelementRepository($entityManager);
        $this->konfiguration = $this->EA_KonfigurationRepository->load();

    }
}