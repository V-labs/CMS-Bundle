<?php

namespace Vlabs\CmsBundle\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PostController extends Controller
{
    public function showAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('VlabsCmsBundle:Front\Post:show.html.twig', [
            'post' => $em
                ->getRepository($this->getParameter('vlabs_cms.post_class'))
                ->findFront($slug),
        ]);
    }
}
