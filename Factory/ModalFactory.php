<?php

namespace Vlabs\CmsBundle\Factory;

use Doctrine\ORM\EntityManager;

/**
 * Class ModalFactory
 * @package Vlabs\CmsBundle\Factory
 */
class ModalFactory
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var string
     */
    private $categoryClass;

    /**
     * @var string
     */
    private $postClass;

    /**
     * @var string
     */
    private $tagClass;

    /**
     * @var string
     */
    private $mediaClass;

    /**
     * @var array
     */
    private $colors;

    /**
     * ModalFactory constructor.
     * @param EntityManager $em
     * @param $categoryClass
     * @param string $postClass
     * @param string $tagClass
     * @param string $mediaClass
     * @param array $colors
     */
    function __construct(EntityManager $em, $categoryClass, $postClass, $tagClass, $mediaClass, array $colors)
    {
        $this->em = $em;
        $this->categoryClass = $categoryClass;
        $this->postClass = $postClass;
        $this->tagClass = $tagClass;
        $this->mediaClass = $mediaClass;
        $this->colors = $colors;
    }

    /**
     * @param $slug
     * @return array
     */
    function getParameters($slug)
    {
        $parameters = [];

        switch ($slug) {
            case 'link':
                $parameters['links'] = $this->em->getRepository($this->postClass)->findLinks();
                break;
            case 'picture':
                $parameters['pictures'] = $this->em->getRepository($this->mediaClass)->findByMimeType('image/%');
                break;
            case 'file':
                $parameters['files'] = $this->em->getRepository($this->mediaClass)->findByMimeType('application/%');
                break;
            case 'style':
                $parameters['colors'] = $this->colors;
                break;
        }

        return $parameters;
    }
}