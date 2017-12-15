<?php

namespace Vlabs\CmsBundle\Manager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Vlabs\CmsBundle\Entity\Category;
use Vlabs\CmsBundle\Event\CategoryEvent;

/**
 * Class CategoryManager
 * @package Vlabs\CmsBundle\Manager
 */
class CategoryManager extends BaseManager
{
    private $eventdispatcher;

    /**
     * PostManager constructor.
     * @param EntityManager $em
     * @param $entityClass
     * @param EventDispatcherInterface $eventdispatcher
     */
    function __construct(EntityManager $em, $entityClass, EventDispatcherInterface $eventdispatcher)
    {
        parent::__construct($em, $entityClass);
        $this->eventdispatcher = $eventdispatcher;
    }

    /**
     * @param Category $category
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Category $category)
    {
        $this->eventdispatcher->dispatch(CategoryEvent::PRE_CREATE, new CategoryEvent($category));
        parent::save($category);
        $this->eventdispatcher->dispatch(CategoryEvent::POST_CREATE, new CategoryEvent($category));
    }

    /**
     * @param $ids
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function sort($ids)
    {
        $i = 1;
        foreach ($ids as $id) {
            $category = $this->getRepository()->find($id);
            $category->setPosition($i++);
        }
        $this->em->flush();
    }
}