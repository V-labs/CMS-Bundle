<?php

namespace Vlabs\CmsBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryEditType extends CategoryType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('section', 'text', [
                'label' => 'category_section'
            ])
            ->add('parent', 'category_parent_tree', [
                'label' => 'category_parent',
                'attr' => ['data-placeholder' => '', 'data-select' => 'categoryParent']
            ])
            ->add('submit', 'submit', [
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
            'data_class' => $this->categoryClass,
            'translation_domain' => 'vlabs_cms'
        ]);
    }
}
