<?php

namespace Vlabs\CmsBundle\Controller\Admin;

use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Vlabs\CmsBundle\Entity\CategoryInterface;
use Vlabs\CmsBundle\Manager\CategoryManager;
use Vlabs\CmsBundle\Repository\CategoryRepository;

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
        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = $this->getDoctrine()->getRepository(
            $this->getParameter('vlabs_cms.category_class')
        );

        return $this->render('@VlabsCms/Admin/Category/index.html.twig', [
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

        /** @var CategoryManager $categoryManager */
        $categoryManager = $this->get('vlabs_cms.manager.category');

        /** @var CategoryInterface $category */
        $category = new $categoryClass();
        $form = $this->createForm($this->getParameter('vlabs_cms.new_category_type'), $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryManager->save($category);
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
        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = $this->getDoctrine()->getRepository(
            $this->getParameter('vlabs_cms.category_class')
        );

        /** @var CategoryInterface $category */
        $category = $categoryRepository->find($id);
        $form = $this->createForm($this->getParameter('vlabs_cms.edit_category_type'), $category);
        $form->handleRequest($request);

        /** @var CategoryManager $categoryManager */
        $categoryManager = $this->get('vlabs_cms.manager.category');

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryManager->save($category);
            $this->addFlash('success', 'category_edited');

            return $this->redirect($this->getBackRoute($category));
        }

        return $this->render('@VlabsCms/Admin/Category/edit.html.twig', [
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
        /** @var CategoryManager $categoryManager */
        $categoryManager = $this->get('vlabs_cms.manager.category');

        $categoryManager->delete($id);

        return new Response();
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function sortAction(Request $request)
    {
        /** @var CategoryManager $categoryManager */
        $categoryManager = $this->get('vlabs_cms.manager.category');

        $categoryManager->sort($request->request);

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
