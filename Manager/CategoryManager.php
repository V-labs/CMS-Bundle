<?php

namespace Vlabs\CmsBundle\Manager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Vlabs\CmsBundle\Entity\Category;
use Vlabs\CmsBundle\Event\CategoryEvent;
/**
 * Class CategoryManager
 * @package Vlabs\CmsBundle\Manager
 */
class CategoryManager extends BaseManager
{
    public function __construct(EntityManager $em, $entityClass, EventDispatcherInterface $eventdispatcher)
    {
        parent::__construct($em, $entityClass, $eventdispatcher);
    }

    /**
     * @param Category $category
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save($category)
    {
        $this->eventdispatcher->dispatch(CategoryEvent::PRE_CREATE, new CategoryEvent($category));
        parent::save($category);
        $this->eventdispatcher->dispatch(CategoryEvent::POST_CREATE, new CategoryEvent($category));
    }

    /**
     * @param ParameterBag $request
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function sort(ParameterBag $request)
    {
        $ids = explode(',', array_keys($request->all())[0]);
        $i = 1;
        foreach ($ids as $id) {
            $category = $this->getRepository()->find($id);
            $category->setPosition($i++);
        }
        $this->em->flush();
    }
}
