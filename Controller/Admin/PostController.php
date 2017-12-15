<?php

namespace Vlabs\CmsBundle\Controller\Admin;

use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Vlabs\CmsBundle\Entity\PostInterface;
use Vlabs\CmsBundle\Form\PostEditType;
use Vlabs\CmsBundle\Form\PostNewType;
use Vlabs\CmsBundle\Entity\CategoryInterface;
use Vlabs\CmsBundle\Manager\PostManager;
use Vlabs\CmsBundle\Repository\CategoryRepository;


/**
 * Class PostController
 * @package Vlabs\CmsBundle\Controller\Admin
 */
class PostController extends Controller implements TranslationContainerInterface
{
    /**
     * @param Request $request
     * @param $categoryId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function newAction(Request $request, $categoryId)
    {
        $this->get('vlabs_cms.manager.tag')->normalizeRequest($request);
        $postClass = $this->getParameter('vlabs_cms.post_class');
        /** @var PostManager $postManager */
        $postManager = $this->get("vlabs_cms.manager.post");

        /** @var PostInterface $post */
        $post = new $postClass();

        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = $this->getDoctrine()->getRepository(
            $this->getParameter('vlabs_cms.category_class')
        );

        /** @var CategoryInterface $category */
        $category = $categoryRepository->find($categoryId);
        $postManager->hydratePost($post, $category);
        $form = $this->createForm($this->getParameter('vlabs_cms.form_post_new.type'), $post);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $postManager->save($post);
            $this->addFlash('success', 'post_created');

            return $this->redirect($this->getBackRoute($post));
        }

        return $this->render('VlabsCmsBundle:Admin\Post:new.html.twig', [
            'form' => $form->createView(),
            'back' => $this->getBackRoute($post)
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function editAction(Request $request, $id)
    {
        $this->get('vlabs_cms.manager.tag')->normalizeRequest($request);
        $em = $this->getDoctrine()->getManager();
        $postClass = $this->getParameter('vlabs_cms.post_class');
        $postRepository = $em->getRepository($postClass);

        /** @var PostInterface $post */
        $post = $postRepository->find($id);
        $form = $this->createForm($this->getParameter('vlabs_cms.form_post_edit.type'), $post);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get('vlabs_cms.manager.post')->save($post);
            $this->addFlash('success', 'post_edited');

            return $this->redirect($this->getBackRoute($post));
        }

        return $this->render('VlabsCmsBundle:Admin\Post:edit.html.twig', [
            'form' => $form->createView(),
            'back' => $this->getBackRoute($post)
        ]);
    }

    /**
     * @param $id
     * @return Response
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function publishAction($id)
    {
        $this->get('vlabs_cms.manager.post')->togglePublish($id);

        return new Response();
    }

    /**
     * @param $id
     * @return Response
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteAction($id)
    {
        $this->get('vlabs_cms.manager.post')->delete($id);
        $this->addFlash('success', 'post_deleted');

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
        $this->get('vlabs_cms.manager.post')->sort($ids);

        return new Response();
    }

    /**
     * @param PostInterface $post
     * @return string
     */
    protected function getBackRoute(PostInterface $post)
    {
        return sprintf(
            '%s#category-%d',
            $this->generateUrl('vlabs_cms_admin_category_index'),
            $post->getRootCategory()->getId()
        );
    }

    /**
     * @return array
     */
    static function getTranslationMessages()
    {
        return [
            new Message('post_created', 'vlabs_cms'),
            new Message('post_edited', 'vlabs_cms'),
            new Message('post_deleted', 'vlabs_cms')
        ];
    }
}
