<?php

namespace Vlabs\CmsBundle\Manager;

class CategoryManager extends BaseManager
{
    public function sort($ids)
    {
        $i = 1;
        foreach($ids as $id) {
            $category = $this->getRepository()->find($id);
            $category->setPosition($i++);
        }
        $this->em->flush();
    }
}
