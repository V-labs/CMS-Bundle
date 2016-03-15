<?php

namespace Vlabs\CmsBundle\Repository;

/**
 * PostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends \Doctrine\ORM\EntityRepository
{
    public function findFront($slug)
    {
        return $this->_em->createQueryBuilder()
            ->select('p')
            ->from($this->_entityName, 'p')
            ->where('p.slug = :slug')
            ->andWhere('p.publishedAt <= :now')
            ->setParameter('slug', $slug)
            ->setParameter('now', new \DateTime())
            ->getQuery()
            ->getSingleResult();
    }
}
