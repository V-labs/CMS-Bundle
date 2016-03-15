<?php

namespace Vlabs\CmsBundle\Manager;

class PostManager extends BaseManager
{
    public function sort($ids)
    {
        $i = 1;
        foreach($ids as $id) {
            $post = $this->getRepository()->find($id);
            $post->setPosition($i++);
        }
        $this->em->flush();
    }

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
