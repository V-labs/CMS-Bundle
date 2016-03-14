<?php

namespace Vlabs\CmsBundle\Controller\Admin;

use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Vlabs\CmsBundle\Entity\CategoryInterface;
use Vlabs\CmsBundle\Form\CategoryEditType;
use Vlabs\CmsBundle\Form\CategoryNewType;

class CategoryController extends Controller implements TranslationContainerInterface
{
    public function indexAction()
    {
        $categoryClass = $this->getParameter('vlabs_cms.category_class');
        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository($categoryClass);
        return $this->render('VlabsCmsBundle:Admin\Category:index.html.twig', [
            'categories' => $categoryRepository->findAll()
        ]);
    }

    public function newAction(Request $request)
    {
        $categoryClass = $this->getParameter('vlabs_cms.category_class');
        /** @var CategoryInterface $category */
        $category = new $categoryClass();
        $postClass = $this->getParameter('vlabs_cms.post_class');
        $form = $this->createForm(new CategoryNewType($categoryClass, $postClass), $category);
        $form->handleRequest($request);
        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
        }
        return new Response();
    }

    public function editAction(Request $request, $id)
    {
        $categoryClass = $this->getParameter('vlabs_cms.category_class');
        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository($categoryClass);
        /** @var CategoryInterface $category */
        $category = $categoryRepository->find($id);
        $postClass = $this->getParameter('vlabs_cms.post_class');
        $form = $this->createForm(new CategoryEditType($categoryClass, $postClass), $category);
        $form->handleRequest($request);
        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'category_edited');
            return $this->redirect($this->getBackRoute($category));
        }
        return $this->render('VlabsCmsBundle:Admin\Category:edit.html.twig', [
            'form' => $form->createView(),
            'back' => $this->getBackRoute($category)
        ]);
    }

    public function deleteAction($id)
    {
        $categoryClass = $this->getParameter('vlabs_cms.category_class');
        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository($categoryClass);
        /** @var CategoryInterface $category */
        $category = $categoryRepository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();
        return new Response();
    }

    public function sortAction(Request $request)
    {
        $categoryClass = $this->getParameter('vlabs_cms.category_class');
        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository($categoryClass);
        $i = 1;
        $ids = explode(',', array_keys($request->request->all())[0]);
        foreach($ids as $id) {
            $category = $categoryRepository->find($id);
            $category->setPosition($i++);
        }
        $em->flush();
        return new Response();
    }

    private function getBackRoute(CategoryInterface $category)
    {
        return sprintf(
            '%s#category-%d',
            $this->generateUrl('vlabs_cms_admin_category_index'),
            $category->getRootParent()->getId()
        );
    }

    static function getTranslationMessages()
    {
        return [
            new Message('category_edited', 'vlabs_cms')
        ];
    }
}
