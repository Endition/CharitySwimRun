<?php
namespace CharitySwimRun\classes\controller;

use Doctrine\ORM\EntityManager;


class EA_StatusVerwaltenController  extends EA_Controller
{
    public function __construct( EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function getPageStatusVerwalten(): string
    {
        $this->EA_TeilnehmerRepository->berechneStati();
        $content = $this->EA_FR->getFormStatusVerwalten($this->entityManager, $this->EA_TeilnehmerRepository->loadList(null,null,null,null,null,null,null,"name","ASC"));
        return $content;
    }

}