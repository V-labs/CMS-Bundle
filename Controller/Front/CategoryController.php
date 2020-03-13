<?php

namespace Vlabs\CmsBundle\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class CategoryController
 * @package Vlabs\CmsBundle\Controller\Front
 */
class CategoryController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $categoryClass = $this->getParameter('vlabs_cms.category_class');
        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository($categoryClass);

        return $this->render('@VlabsCms/Front/Category/index.html.twig', [
            'categories' => $categoryRepository->findAll()
        ]);
    }
}
