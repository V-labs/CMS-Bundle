<?php

namespace Vlabs\CmsBundle\Manager;

use Doctrine\ORM\EntityManager;
use Vlabs\CmsBundle\Entity\TagInterface;
use Symfony\Component\HttpFoundation\Request;

class TagManager
{
    private $em;
    private $tagClass;

    function __construct(EntityManager $em, $tagClass)
    {
        $this->em = $em;
        $this->tagClass = $tagClass;
    }

    public function normalizeRequest(Request $request)
    {
        if (!$request->request->has('tags')) {
            return;
        }
        $tagIds = $request->request->get('tags');
        foreach ($tagIds as $i => $tagId) {
            $tagRepository = $this->em->getRepository($this->tagClass);
            if ($tagRepository->find($tagId)) continue;
            /** @var TagInterface $tag */
            $tag = new $this->tagClass();
            $tag->setName($tagId);
            $this->em->persist($tag);
            $this->em->flush();
            $tagIds[$i] = $tag->getId();
        }
        $request->request->set('tags', $tagIds);
    }
}
