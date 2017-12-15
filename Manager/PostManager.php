<?php

namespace Vlabs\CmsBundle\Manager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Vlabs\CmsBundle\Entity\Post;
use Vlabs\CmsBundle\Event\PostEvent;

/**
 * Class PostManager
 * @package Vlabs\CmsBundle\Manager
 */
class PostManager extends BaseManager
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
     * @param Post $post
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Post $post)
    {
        $this->eventdispatcher->dispatch(PostEvent::PRE_CREATE, new PostEvent($post));
        parent::save($post);
        $this->eventdispatcher->dispatch(PostEvent::POST_CREATE, new PostEvent($post));
    }

    /**
     * @param $ids
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function sort($ids)
    {
        $i = 1;
        foreach ($ids as $id) {
            $post = $this->getRepository()->find($id);
            $post->setPosition($i++);
        }
        $this->em->flush();
    }

    /**
     * @param $id
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function togglePublish($id)
    {
        $post = $this->getRepository()->find($id);
        if ($post->getPublishedAt()) {
            $post->setPublishedAt(null);
            $post->setUnpublishedAt(new \DateTime());
        } else {
            $post->setPublishedAt(new \DateTime());
            $post->setUnpublishedAt(null);
        }
        $this->em->flush();
    }
}