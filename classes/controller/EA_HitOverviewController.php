<?php
namespace CharitySwimRun\classes\controller;

use Doctrine\ORM\EntityManager;

class EA_HitOverviewController extends EA_Controller
{
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function getPageBuchungsuebersicht(): string
    {
        $content = "";
        $content .= $this->EA_FR->getFormBuchungsuebersicht($this->entityManager, $this->EA_HitRepository->loadList("i.timestamp","DESC"));
        return $content;
    }

}