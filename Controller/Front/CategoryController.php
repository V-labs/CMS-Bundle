<?php

namespace Vlabs\CmsBundle\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryController extends Controller
{
    public function indexAction()
    {
        $categoryClass = $this->getParameter('vlabs_cms.category_class');
        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository($categoryClass);

        return $this->render('VlabsCmsBundle:Front\Category:index.html.twig', [
            'categories' => $categoryRepository->findAll()
        ]);
    }
}
