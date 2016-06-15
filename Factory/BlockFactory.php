<?php

namespace Vlabs\CmsBundle\Factory;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\ParameterBag;

class BlockFactory
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
     * @param EntityManager $em
     * @param $categoryClass
     * @param $postClass
     * @param $tagClass
     * @param $mediaClass
     */
    function __construct(EntityManager $em, $categoryClass, $postClass, $tagClass, $mediaClass)
    {
        $this->em = $em;
        $this->categoryClass = $categoryClass;
        $this->postClass = $postClass;
        $this->tagClass = $tagClass;
        $this->mediaClass = $mediaClass;
    }

    function getParameters($slug, ParameterBag $parameterBag)
    {
        $parameters = [];

        switch($slug){
            case 'style':
                $parameters['color'] = $parameterBag->get('color');
                $parameters['text'] = $parameterBag->get('text');
                break;
            case 'link':
                $id = $parameterBag->get('id');
                $parameters['post'] = $this->em->getRepository($this->postClass)->find($id);
                break;
            case 'url':
                $parameters['url'] = $parameterBag->get('url');
                $parameters['text'] = $parameterBag->get('text');
                break;
            case 'picture':
                $id = $parameterBag->get('id');
                $parameters['picture'] = $this->em->getRepository($this->mediaClass)->find($id);
                break;
            case 'pdf':
                $id = $parameterBag->get('id');
                $parameters['pdf'] = $this->em->getRepository($this->mediaClass)->find($id);
                break;
        }

        return $parameters;
    }
}
