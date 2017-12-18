<?php

namespace Vlabs\CmsBundle\Manager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
     * @var EventDispatcherInterface
     */
    protected  $eventdispatcher;

    /**
     * BaseManager constructor.
     * @param EntityManager $em
     * @param $entityClass
     * @param EventDispatcherInterface $eventdispatcher
     */
    function __construct(EntityManager $em, $entityClass, EventDispatcherInterface $eventdispatcher)
    {
        $this->em = $em;
        $this->entityClass = $entityClass;
        $this->eventdispatcher = $eventdispatcher;
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