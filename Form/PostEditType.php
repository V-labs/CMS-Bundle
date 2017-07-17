<?php

namespace Vlabs\CmsBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostEditType extends PostType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('category', CategoryTreeType::class, [
                'label' => 'post_category',
                'attr' => ['data-select' => 'postCategory']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'submit',
                'attr' => [ 'class' => 'btn-primary' ]
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => 'PUT',
            'allow_extra_fields' => true,
            'data_class' => $this->postClass,
            'translation_domain' => 'vlabs_cms'
        ]);
    }
}
