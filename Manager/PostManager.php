<?php

namespace Vlabs\CmsBundle\Manager;

use Symfony\Component\HttpFoundation\ParameterBag;
use Vlabs\CmsBundle\Entity\CategoryInterface;
use Vlabs\CmsBundle\Entity\Post;
use Vlabs\CmsBundle\Entity\PostInterface;
use Vlabs\CmsBundle\Event\PostEvent;
/**
 * Class PostManager
 * @package Vlabs\CmsBundle\Manager
 */
class PostManager extends BaseManager
{

    /**
     * @param PostInterface $post
     * @param CategoryInterface $category
     */
    public function hydratePost(PostInterface $post, CategoryInterface $category)
    {
        $post->setCategory($category);
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
     * @param ParameterBag $request
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function sort(ParameterBag $request)
    {
        $ids = explode(',', array_keys($request->all())[0]);
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