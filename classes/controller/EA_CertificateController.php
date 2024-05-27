<?php
namespace CharitySwimRun\classes\controller;

use Doctrine\ORM\EntityManager;



class EA_CertificateController extends EA_Controller
{
    
    public function __construct( EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }
    
    public function getPageUrkunden(): string
    {
        $content = $this->EA_FR->getContentUrkunden($this->entityManager, 
            $this->EA_StarterRepository->loadStreckenAltersklassenTeilnehmerVerteilung(), 
            $this->EA_StarterRepository->loadStreckenAltersklassenTeilnehmerVerteilung(true));
        return $content;
    }
}