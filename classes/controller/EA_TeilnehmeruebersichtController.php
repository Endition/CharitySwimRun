<?php
namespace EndeAuswertung\classes\controller;

use Doctrine\ORM\EntityManager;



class EA_TeilnehmeruebersichtController extends EA_Controller
{

    public function __construct( EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function getPageTeilnehmeruebersicht(): string
    {
        $content = "";
        $content .= $this->EA_FR->getFormTeilnehmeruebersicht($this->entityManager, $this->EA_TeilnehmerRepository->loadList());
        return $content;
    }

}