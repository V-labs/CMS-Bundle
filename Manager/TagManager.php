<?php

namespace Vlabs\CmsBundle\Manager;

use Doctrine\ORM\EntityManager;
use Vlabs\CmsBundle\Entity\TagInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TagManager
 * @package Vlabs\CmsBundle\Manager
 */
class TagManager
{
    /**
     * @var EntityManager
     */
    private $em;
    private $tagClass;

    /**
     * TagManager constructor.
     * @param EntityManager $em
     * @param $tagClass
     */
    function __construct(EntityManager $em, $tagClass)
    {
        $this->em = $em;
        $this->tagClass = $tagClass;
    }

    /**
     * @param Request $request
     * @throws \Doctrine\ORM\OptimisticLockException
     */
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