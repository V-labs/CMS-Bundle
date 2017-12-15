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

/**
 * Class CategoryController
 * @package Vlabs\CmsBundle\Controller\Admin
 */
class CategoryController extends Controller implements TranslationContainerInterface
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        $categoryClass = $this->getParameter('vlabs_cms.category_class');
        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository($categoryClass);

        return $this->render('VlabsCmsBundle:Admin\Category:index.html.twig', [
            'categories' => $categoryRepository->findAll()
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function newAction(Request $request)
    {
        $categoryClass = $this->getParameter('vlabs_cms.category_class');

        /** @var CategoryInterface $category */
        $category = new $categoryClass();
        $form = $this->createForm(CategoryNewType::class, $category);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get('vlabs_cms.manager.category')->save($category);
        }

        return new Response();
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function editAction(Request $request, $id)
    {
        $categoryClass = $this->getParameter('vlabs_cms.category_class');
        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository($categoryClass);

        /** @var CategoryInterface $category */
        $category = $categoryRepository->find($id);
        $form = $this->createForm(CategoryEditType::class, $category);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get('vlabs_cms.manager.category')->save($category);
            $this->addFlash('success', 'category_edited');

            return $this->redirect($this->getBackRoute($category));
        }

        return $this->render('VlabsCmsBundle:Admin\Category:edit.html.twig', [
            'form' => $form->createView(),
            'back' => $this->getBackRoute($category)
        ]);
    }

    /**
     * @param $id
     * @return Response
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteAction($id)
    {
        $this->get('vlabs_cms.manager.category')->delete($id);

        return new Response();
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function sortAction(Request $request)
    {
        $ids = explode(',', array_keys($request->request->all())[0]);
        $this->get('vlabs_cms.manager.category')->sort($ids);

        return new Response();
    }

    /**
     * @param CategoryInterface $category
     * @return string
     */
    protected function getBackRoute(CategoryInterface $category)
    {
        return sprintf(
            '%s#category-%d',
            $this->generateUrl('vlabs_cms_admin_category_index'),
            $category->getRootParent()->getId()
        );
    }

    /**
     * @return array
     */
    static function getTranslationMessages()
    {
        return [
            new Message('category_edited', 'vlabs_cms')
        ];
    }
}
