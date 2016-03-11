<?php

namespace Edf\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Vlabs\CmsBundle\Entity\Category;
use Vlabs\CmsBundle\Entity\Color;
use Vlabs\CmsBundle\Form\CategoryEditColorType;
use Vlabs\CmsBundle\Form\CategoryEditType;
use Vlabs\CmsBundle\Form\CategoryNewType;

class CategoryController extends Controller
{
    public function newAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(new CategoryNewType(), $category);
        $form->handleRequest($request);
        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
        }
        return new Response();
    }

    public function editAction(Request $request, Category $category)
    {
        $form = $this->createForm(new CategoryEditType(), $category);
        $form->handleRequest($request);
        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'Catégorie éditée avec succès');
            return $this->redirect($this->getBackRoute($category));
        }
        return $this->render('EdfAdminBundle:Category:edit.html.twig', [
            'form' => $form->createView(),
            'back' =>$this->getBackRoute($category)
        ]);
    }

    public function deleteAction(Category $category)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        return new Response();
    }

    public function sortAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository('VlabsCmsBundle:Category');
        $i = 1;
        $ids = explode(',', array_keys($request->request->all())[0]);
        foreach($ids as $id) {
            $category = $categoryRepository->find($id);
            $category->setPosition($i++);
        }
        $em->flush();

        return new Response();
    }

    private function getBackRoute(Category $category)
    {
        $hash = sprintf('#category-%d', $category->getRootParent()->getId());
        return $this->generateUrl('edf_admin_homepage') . $hash;
    }
}
