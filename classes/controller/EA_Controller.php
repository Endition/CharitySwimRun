<?php
namespace CharitySwimRun\classes\controller;

use Doctrine\ORM\EntityManager;

use CharitySwimRun\classes\model\EA_TeilnehmerRepository;
use CharitySwimRun\classes\model\EA_KonfigurationRepository;
use CharitySwimRun\classes\renderer\EA_FormRenderer;
use CharitySwimRun\classes\renderer\EA_Renderer;
use CharitySwimRun\classes\model\EA_Messages;
use CharitySwimRun\classes\model\EA_AgeGroupRepository;
use CharitySwimRun\classes\model\EA_ImpulsRepository;
use CharitySwimRun\classes\model\EA_Konfiguration;
use CharitySwimRun\classes\model\EA_ClubRepository;
use CharitySwimRun\classes\model\EA_TeamRepository;
use CharitySwimRun\classes\model\EA_SpecialEvaluationRepository;
use CharitySwimRun\classes\model\EA_DistanceRepository;
use CharitySwimRun\classes\model\EA_TeamCategoryRepository;
use CharitySwimRun\classes\model\EA_UserRepository;
use CharitySwimRun\classes\model\EA_UrkundenelementRepository;

class EA_Controller
{
    protected ?EntityManager $entityManager;

    protected EA_Renderer $EA_R;
    protected EA_FormRenderer $EA_FR;
    protected EA_AgeGroupRepository $EA_AgeGroupRepository;
    protected EA_TeilnehmerRepository $EA_TeilnehmerRepository;
    protected EA_KonfigurationRepository $EA_KonfigurationRepository;
    protected EA_ImpulsRepository $EA_ImpulsRepository;
    protected EA_ClubRepository $vereinRepository;
    protected EA_TeamRepository $EA_TeamRepository;
    protected EA_SpecialEvaluationRepository $EA_SpecialEvaluationRepository;
    protected EA_ClubRepository $EA_ClubRepository;
    protected EA_DistanceRepository $EA_DistanceRepository;
    protected EA_TeamCategoryRepository $EA_TeamCategoryRepository;
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
        $this->EA_AgeGroupRepository = new EA_AgeGroupRepository($entityManager);
        $this->EA_TeilnehmerRepository = new EA_TeilnehmerRepository($entityManager);
        $this->EA_KonfigurationRepository = new EA_KonfigurationRepository($entityManager);
        $this->EA_ImpulsRepository = new EA_ImpulsRepository($entityManager);
        $this->EA_TeamRepository = new EA_TeamRepository($entityManager);
        $this->EA_SpecialEvaluationRepository = new EA_SpecialEvaluationRepository($entityManager);
        $this->EA_ClubRepository = new EA_ClubRepository($entityManager);
        $this->EA_DistanceRepository = new EA_DistanceRepository($entityManager);
        $this->EA_TeamCategoryRepository = new EA_TeamCategoryRepository($entityManager);
        $this->EA_UrkundenelementRepository = new EA_UrkundenelementRepository($entityManager);
        $this->konfiguration = $this->EA_KonfigurationRepository->load();

    }
}