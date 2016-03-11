<?php

namespace Vlabs\CmsBundle\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('VlabsCmsBundle:Front\Category:index.html.twig', [
            'categories' => $em
                ->getRepository($this->getParameter('vlabs_cms.category_class'))
                ->findAllFront(),
        ]);
    }
}
