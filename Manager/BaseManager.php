<?php

namespace Vlabs\CmsBundle\Manager;

use Doctrine\ORM\EntityManager;
use Vlabs\CmsBundle\Entity\TagInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BaseManager
 * @package Vlabs\CmsBundle\Manager
 */
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

    /**
     * BaseManager constructor.
     * @param EntityManager $em
     * @param $entityClass
     */
    function __construct(EntityManager $em, $entityClass)
    {
        $this->em = $em;
        $this->entityClass = $entityClass;
    }

    /**
     * @param $entity
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    /**
     * @param $id
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete($id)
    {
        $this->em->remove($this->getRepository()->find($id));
        $this->em->flush();
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository()
    {
        return $this->em->getRepository($this->entityClass);
    }
}