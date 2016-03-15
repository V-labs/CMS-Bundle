<?php

namespace Vlabs\CmsBundle\Manager;

use Doctrine\ORM\EntityManager;
use Vlabs\CmsBundle\Entity\TagInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseManager
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var string
     */
    protected $entityClass;


    function __construct(EntityManager $em, $entityClass)
    {
        $this->em = $em;
        $this->entityClass = $entityClass;
    }

    public function save($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function delete($id)
    {
        $this->em->remove($this->getRepository()->find($id));
        $this->em->flush();
    }

    protected function getRepository()
    {
        return $this->em->getRepository($this->entityClass);
    }
}
