<?php

namespace Vlabs\CmsBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Vlabs\CmsBundle\Factory\ModalFactory;
use Vlabs\MediaBundle\Form\MediaType;

/**
 * Class SummernoteController
 * @package Vlabs\CmsBundle\Controller\Admin
 */
class SummernoteController extends Controller
{
    /**
     * @param Request $request
     * @param $slug
     * @return Response
     */
    public function modalAction(Request $request, $slug)
    {
        /** @var ModalFactory $modalFactory */
        $modalFactory = $this->get('vlabs_cms.factory.modal');

        $params = $modalFactory->getParameters($slug, $request->query);

        return $this->render(sprintf('VlabsCmsBundle:Admin\Summernote\Modal:%s.html.twig', $slug), $params);
    }

    /**
     * @param Request $request
     * @param $slug
     * @return Response
     */
    public function blockAction(Request $request, $slug)
    {
        /** @var ModalFactory $modalFactory */
        $modalFactory = $this->get('vlabs_cms.factory.modal');

        $params = $modalFactory->getParameters($slug, $request->query);

        return $this->render(sprintf('VlabsCmsBundle:Admin\Summernote\Block:%s.html.twig', $slug), $params);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function mediaAction(Request $request)
    {
        $mediaClass = $this->getParameter('vlabs_cms.media_class');

        $media = new $mediaClass();

        $form = $this->createForm(MediaType::class, $media);

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
