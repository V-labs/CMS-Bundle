<?php

namespace Vlabs\CmsBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Vlabs\CmsBundle\Entity\Post;

class PostEvent extends Event
{
    private $post;

    const PRE_CREATE = 'post_pre_create';
    const POST_CREATE = 'post_post_create';

    /**
     * PostEvent constructor.
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

}