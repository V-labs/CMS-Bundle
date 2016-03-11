<?php

namespace Edf\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Vlabs\CmsBundle\Entity\Category;
use Vlabs\CmsBundle\Entity\Post;
use Vlabs\CmsBundle\Form\PostEditType;
use Vlabs\CmsBundle\Form\PostNewType;

class PostController extends Controller
{
    public function newAction(Request $request, Category $category)
    {
        $this->get('vlabs_cms.manager.tag')->normalizeRequest($request);
        $post = new Post();
        $post->setCategory($category);
        $form = $this->createForm(new PostNewType(), $post);
        $form->handleRequest($request);
        if($form->isValid()){
            $this->get('vlabs_cms.manager.post')->compile($post);
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            $this->addFlash('success', 'Article créé avec succès');
            return $this->redirect($this->getBackRoute($post));
        }
        return $this->render('EdfAdminBundle:Post:new.html.twig', [
            'form' => $form->createView(),
            'back' =>$this->getBackRoute($post)
        ]);
    }

    public function editAction(Request $request, Post $post)
    {
        $this->get('vlabs_cms.manager.tag')->normalizeRequest($request);
        $form = $this->createForm(new PostEditType(), $post);
        $form->handleRequest($request);
        if($form->isValid()){
            $this->get('vlabs_cms.manager.post')->compile($post);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Article édité avec succès');
            return $this->redirect($this->getBackRoute($post));
        }
        return $this->render('EdfAdminBundle:Post:edit.html.twig', [
            'form' => $form->createView(),
            'back' =>$this->getBackRoute($post)
        ]);
    }

    public function publishAction(Post $post)
    {
        if ($post->getPublishedAt()) {
            $post->setPublishedAt(null);
            $post->setUnpublishedAt(new \DateTime());
        } else {
            $post->setPublishedAt(new \DateTime());
            $post->setUnpublishedAt(null);
        }
        $this->getDoctrine()->getManager()->flush();
        return new Response();
    }

    public function deleteAction(Post $post)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return new Response();
    }

    public function sortAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $postRepository = $em->getRepository('VlabsCmsBundle:Post');
        $i = 1;
        $ids = explode(',', array_keys($request->request->all())[0]);
        foreach($ids as $id) {
            $post = $postRepository->find($id);
            $post->setPosition($i++);
        }
        $em->flush();

        return new Response();
    }

    private function getBackRoute(Post $post)
    {
        $hash = sprintf('#category-%d', $post->getRootCategory()->getId());
        return $this->generateUrl('edf_admin_homepage') . $hash;
    }
}
