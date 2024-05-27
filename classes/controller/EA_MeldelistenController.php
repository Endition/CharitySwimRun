<?php
namespace CharitySwimRun\classes\controller;

use Doctrine\ORM\EntityManager;


class EA_MeldelistenController  extends EA_Controller
{

    public function __construct( EntityManager $entityManager)
    {
        parent::__construct($entityManager);

    }
    public function getPageMeldelisten(): string
    {
        $content = $this->EA_FR->getContentMeldelisten($this->entityManager, $this->EA_TeilnehmerRepository->loadStreckenAltersklassenTeilnehmerVerteilung(), $this->EA_TeilnehmerRepository->loadStreckenAltersklassenTeilnehmerVerteilung(true));
        return $content;
    }
}