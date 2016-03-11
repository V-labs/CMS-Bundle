<?php

namespace Vlabs\CmsBundle\Manager;

use Doctrine\ORM\EntityManager;
use Vlabs\CmsBundle\Entity\TagInterface;
use Symfony\Component\HttpFoundation\Request;

class TagManager
{
    private $em;

    function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function normalizeRequest(Request $request)
    {
        if (!$request->request->has('tags')) {
            return;
        }
        $tagIds = $request->request->get('tags');
        foreach ($tagIds as $i => $tagId) {
            $tagRepository = $this->em->getRepository('VlabsCmsBundle:Tag');
            if ($tagRepository->find($tagId)) continue;
            $tag = new Tag();
            $tag->setName($tagId);
            $this->em->persist($tag);
            $this->em->flush();
            $tagIds[$i] = $tag->getId();
        }
        $request->request->set('tags', $tagIds);
    }
}
