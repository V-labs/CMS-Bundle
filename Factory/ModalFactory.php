<?php

namespace Vlabs\CmsBundle\Factory;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\ParameterBag;

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
     * @param EntityManager $em
     * @param $categoryClass
     * @param $postClass
     * @param $tagClass
     * @param $mediaClass
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

    function getParameters($slug, ParameterBag $parameterBag)
    {
        $parameters = [];

        switch ($slug) {
            case 'link':
                $parameters['links'] = $this->em->getRepository($this->postClass)->findLinks();
                break;
            case 'picture':
                $parameters['pictures'] = $this->em->getRepository($this->mediaClass)->findByMimeType('image/%');
                break;
            case 'pdf':
                $parameters['pdfs'] = $this->em->getRepository($this->mediaClass)->findByMimeType('application/pdf');
                break;
            case 'style':
                $parameters['colors'] = $this->colors;
                $parameters['class'] = $parameterBag->get('class');
                break;
            case 'word':
                $parameters['words'] = $this->em->getRepository($this->mediaClass)->findByMimeTypes([
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                ]);
                break;
        }
        return $parameters;
    }
}
