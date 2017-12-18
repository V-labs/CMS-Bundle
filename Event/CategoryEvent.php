<?php

namespace Vlabs\CmsBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Vlabs\CmsBundle\Entity\Category;

class CategoryEvent extends Event
{
    private $category;

    const PRE_CREATE = 'category_pre_create';
    const POST_CREATE = 'category_post_create';

    /**
     * PostEvent constructor.
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

}