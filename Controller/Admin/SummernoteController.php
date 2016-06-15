<?php

namespace Vlabs\CmsBundle\Controller\Admin;

use AppBundle\Entity\Media;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Vlabs\MediaBundle\Form\MediaType;

class SummernoteController extends Controller
{
    public function modalAction(Request $request, $slug)
    {
        $params = $this->get('vlabs_cms.factory.modal')->getParameters($slug, $request->query);
        return $this->render(sprintf('VlabsCmsBundle:Admin\Summernote\Modal:%s.html.twig', $slug), $params);
    }

    public function blockAction(Request $request, $slug)
    {
        $params = $this->get('vlabs_cms.factory.block')->getParameters($slug, $request->query);
        return $this->render(sprintf('VlabsCmsBundle:Admin\Summernote\Block:%s.html.twig', $slug), $params);
    }

    public function mediaAction(Request $request)
    {
        $media = new Media();

        $form = $this->createForm(new MediaType(get_class($media)), $media);
        $form->add('upload', 'submit');

        $form->handleRequest($request);

        if (is_null($media->getMediaFile())) {
            return new Response(null, 400);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($media);
        $em->flush();

        return new Response($media->getId(), 201);
    }
}