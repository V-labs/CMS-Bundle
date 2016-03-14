<?php

namespace Vlabs\CmsBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    protected $postClass;
    protected $tagClass;

    function __construct($postClass, $tagClass)
    {
        $this->postClass = $postClass;
        $this->tagClass = $tagClass;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', [
                'label' => 'post_title'
            ])
            ->add('content', 'textarea', [
                'label' => 'post_content',
                'attr' => [
                    'data-editor' => 'postContent'
                ]
            ])
            ->add('relatedPosts', 'entity', [
                'label' => 'post_related_posts',
                'class' => $this->postClass,
                'property' => 'title',
                'attr' => ['data-select' => 'postRelatedPosts'],
                'multiple' => true,
                'query_builder' => function (EntityRepository $r) {
                    return $r->createQueryBuilder('p')
                        ->orderBy('p.title', 'ASC');
                },
                'required' => false
            ])
            ->add('tags', 'entity', [
                'label' => 'post_tags',
                'class' => $this->tagClass,
                'property' => 'name',
                'attr' => ['data-select' => 'postTags'],
                'multiple' => true,
                'query_builder' => function (EntityRepository $r) {
                    return $r->createQueryBuilder('t')
                        ->orderBy('t.name', 'ASC');
                },
                'required' => false
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'vlabs_cms'
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return '';
    }
}
