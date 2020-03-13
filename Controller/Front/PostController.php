<?php

namespace Vlabs\CmsBundle\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class PostController
 * @package Vlabs\CmsBundle\Controller\Front
 */
class PostController extends Controller
{
    /**
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('@VlabsCms/Front/Post/show.html.twig', [
            'post' => $em
                ->getRepository($this->getParameter('vlabs_cms.post_class'))
                ->findFront($slug),
        ]);
    }
}
