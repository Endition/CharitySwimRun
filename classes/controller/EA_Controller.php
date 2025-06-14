<?php
namespace CharitySwimRun\classes\controller;

use Doctrine\ORM\EntityManager;

use CharitySwimRun\classes\model\EA_StarterRepository;
use CharitySwimRun\classes\model\EA_ConfigurationRepository;
use CharitySwimRun\classes\renderer\EA_FormRenderer;
use CharitySwimRun\classes\renderer\EA_Renderer;
use CharitySwimRun\classes\model\EA_Messages;
use CharitySwimRun\classes\model\EA_AgeGroupRepository;
use CharitySwimRun\classes\model\EA_HitRepository;
use CharitySwimRun\classes\model\EA_Configuration;
use CharitySwimRun\classes\model\EA_ClubRepository;
use CharitySwimRun\classes\model\EA_CompanyRepository;
use CharitySwimRun\classes\model\EA_TeamRepository;
use CharitySwimRun\classes\model\EA_SpecialEvaluationRepository;
use CharitySwimRun\classes\model\EA_DistanceRepository;
use CharitySwimRun\classes\model\EA_TeamCategoryRepository;
use CharitySwimRun\classes\model\EA_UserRepository;
use CharitySwimRun\classes\model\EA_CertificateElementRepository;

class EA_Controller
{
    protected ?EntityManager $entityManager;

    protected EA_Renderer $EA_R;
    protected EA_FormRenderer $EA_FR;
    protected EA_AgeGroupRepository $EA_AgeGroupRepository;
    protected EA_StarterRepository $EA_StarterRepository;
    protected EA_ConfigurationRepository $EA_ConfigurationRepository;
    protected EA_HitRepository $EA_HitRepository;
    protected EA_ClubRepository $vereinRepository;
    protected EA_CompanyRepository $EA_CompanyRepository;
    protected EA_TeamRepository $EA_TeamRepository;
    protected EA_SpecialEvaluationRepository $EA_SpecialEvaluationRepository;
    protected EA_ClubRepository $EA_ClubRepository;
    protected EA_DistanceRepository $EA_DistanceRepository;
    protected EA_TeamCategoryRepository $EA_TeamCategoryRepository;
    protected EA_UserRepository $EA_UserRepository;
    protected EA_CertificateElementRepository $EA_CertificateElementRepository;  
  

    protected EA_Messages $EA_Messages;
    protected ?EA_Configuration $konfiguration;

    
    public function __construct(EntityManager $entityManager){
        $this->entityManager = $entityManager;
        
        $this->EA_FR = new EA_FormRenderer();
        $this->EA_R = new EA_Renderer();
        $this->EA_Messages = new EA_Messages();
        $this->EA_UserRepository = new EA_UserRepository($entityManager);
        $this->EA_AgeGroupRepository = new EA_AgeGroupRepository($entityManager);
        $this->EA_StarterRepository = new EA_StarterRepository($entityManager);
        $this->EA_ConfigurationRepository = new EA_ConfigurationRepository($entityManager);
        $this->EA_HitRepository = new EA_HitRepository($entityManager);
        $this->EA_TeamRepository = new EA_TeamRepository($entityManager);
        $this->EA_SpecialEvaluationRepository = new EA_SpecialEvaluationRepository($entityManager);
        $this->EA_CompanyRepository = new EA_CompanyRepository($entityManager);
        $this->EA_ClubRepository = new EA_ClubRepository($entityManager);
        $this->EA_DistanceRepository = new EA_DistanceRepository($entityManager);
        $this->EA_TeamCategoryRepository = new EA_TeamCategoryRepository($entityManager);
        $this->EA_CertificateElementRepository = new EA_CertificateElementRepository($entityManager);
        $this->konfiguration = $this->EA_ConfigurationRepository->load();

    }
}