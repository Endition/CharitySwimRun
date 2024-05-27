<?php
namespace CharitySwimRun\classes\controller;

use Doctrine\ORM\EntityManager;


class EA_StarterStatusAdminController  extends EA_Controller
{
    public function __construct( EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function getPageStatusVerwalten(): string
    {
        $this->EA_StarterRepository->berechneStati();
        $content = $this->EA_FR->getFormStatusVerwalten($this->entityManager, $this->EA_StarterRepository->loadList(null,null,null,null,null,null,null,"name","ASC"));
        return $content;
    }

}