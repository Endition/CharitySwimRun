<?php
namespace CharitySwimRun\classes\controller;

use Doctrine\ORM\EntityManager;



class EA_StarteruebersichtController extends EA_Controller
{

    public function __construct( EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function getPageTeilnehmeruebersicht(): string
    {
        $content = "";
        $content .= $this->EA_FR->getFormTeilnehmeruebersicht($this->entityManager, $this->EA_StarterRepository->loadList());
        return $content;
    }

}