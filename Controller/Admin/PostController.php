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

class PostController extends Controller implements TranslationContainerInterface
{
    public function newAction(Request $request, $categoryId)
    {
        $this->get('vlabs_cms.manager.tag')->normalizeRequest($request);
        $em = $this->getDoctrine()->getManager();
        $postClass = $this->getParameter('vlabs_cms.post_class');
        /** @var PostInterface $post */
        $post = new $postClass();
        $categoryClass = $this->getParameter('vlabs_cms.category_class');
        $categoryRepository = $em->getRepository($categoryClass);
        /** @var CategoryInterface $category */
        $category = $categoryRepository->find($categoryId);
        $post->setCategory($category);
        $form = $this->createForm(PostNewType::class, $post);
        $form->handleRequest($request);
        if($form->isValid()){
            $this->get('vlabs_cms.manager.post')->save($post);
            $this->addFlash('success', 'post_created');
            return $this->redirect($this->getBackRoute($post));
        }
        return $this->render('VlabsCmsBundle:Admin\Post:new.html.twig', [
            'form' => $form->createView(),
            'back' =>$this->getBackRoute($post)
        ]);
    }

    public function editAction(Request $request, $id)
    {
        $this->get('vlabs_cms.manager.tag')->normalizeRequest($request);
        $em = $this->getDoctrine()->getManager();
        $postClass = $this->getParameter('vlabs_cms.post_class');
        $postRepository = $em->getRepository($postClass);
        /** @var PostInterface $post */
        $post = $postRepository->find($id);
        $form = $this->createForm(PostEditType::class, $post);
        $form->handleRequest($request);
        if($form->isValid()){
            $this->get('vlabs_cms.manager.post')->save($post);
            $this->addFlash('success', 'post_edited');
            return $this->redirect($this->getBackRoute($post));
        }
        return $this->render('VlabsCmsBundle:Admin\Post:edit.html.twig', [
            'form' => $form->createView(),
            'back' =>$this->getBackRoute($post)
        ]);
    }

    public function publishAction($id)
    {
        $this->get('vlabs_cms.manager.post')->togglePublish($id);
        return new Response();
    }

    public function deleteAction($id)
    {
        $this->get('vlabs_cms.manager.post')->delete($id);
        $this->addFlash('success', 'post_deleted');
        return new Response();
    }

    public function sortAction(Request $request)
    {
        $ids = explode(',', array_keys($request->request->all())[0]);
        $this->get('vlabs_cms.manager.post')->sort($ids);
        return new Response();
    }

    protected function getBackRoute(PostInterface $post)
    {
        return sprintf(
            '%s#category-%d',
            $this->generateUrl('vlabs_cms_admin_category_index'),
            $post->getRootCategory()->getId()
        );
    }

    static function getTranslationMessages()
    {
        return [
            new Message('post_created', 'vlabs_cms'),
            new Message('post_edited', 'vlabs_cms'),
            new Message('post_deleted', 'vlabs_cms')
        ];
    }
}
