<?php

namespace Vlabs\CmsBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('title', TextType::class, [
                'label' => 'post_title'
            ])
            ->add('content', TextareaType::class, [
                'label' => 'post_content',
                'attr' => [
                    'data-editor' => 'postContent'
                ]
            ])
            ->add('relatedPosts', EntityType::class, [
                'label' => 'post_related_posts',
                'class' => $this->postClass,
                'choice_label' => 'title',
                'attr' => ['data-select' => 'postRelatedPosts'],
                'multiple' => true,
                'query_builder' => function (EntityRepository $r) {
                    return $r->createQueryBuilder('p')
                        ->orderBy('p.title', 'ASC');
                },
                'required' => false
            ])
            ->add('tags', EntityType::class, [
                'label' => 'post_tags',
                'class' => $this->tagClass,
                'choice_label' => 'name',
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
