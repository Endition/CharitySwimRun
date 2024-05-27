<?php
namespace CharitySwimRun\classes\model;

use Doctrine\ORM\EntityManager;
use CharitySwimRun\classes\model\EA_CertificateElement;


class EA_CertificateElementRepository extends EA_Repository
{
    private EntityManager $entityManager;

        //own constructor is necassery, therefore all repositories use the same entitymanager
    public function __construct(EntityManager $entitymanager)
    {
        $this->entityManager = $entitymanager;
                //we need the same entityManager in the motherclass
                parent::setEntityManager($entitymanager); 
    }

    public function create(EA_CertificateElement $urkundenelement): EA_CertificateElement
    {
        $this->entityManager->persist($urkundenelement);
        $this->update();
        return $urkundenelement;
    }

    public function loadById(int $id): ?EA_CertificateElement
    {
        return $this->entityManager->getRepository('CharitySwimRun\classes\model\EA_CertificateElement')->find($id);
    }

    public function loadList(): array
    {
        return $this->entityManager->getRepository("CharitySwimRun\classes\model\EA_CertificateElement")->findAll();
    }

    public function loadListQueryBuilder(): array
    {
        //https://www.doctrine-project.org/projects/doctrine-orm/en/3.1/reference/query-builder.html
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('u')
            ->from(EA_CertificateElement::class, 'u');
        return $qb->getQuery()->getResult();
    }

    public function delete(EA_CertificateElement $urkundenelement): void
    {
        $this->entityManager->remove($urkundenelement);
        $this->update();
    }

}