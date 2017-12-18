<?php

namespace Vlabs\CmsBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CategoryType
 * @package Vlabs\CmsBundle\Form
 */
class CategoryType extends AbstractType
{
    /**
     * @var string
     */
    protected $categoryClass;
    /**
     * @var string
     */
    protected $postClass;

    /**
     * CategoryType constructor.
     * @param string $categoryClass
     * @param string $postClass
     */
    function __construct($categoryClass, $postClass)
    {
        $this->categoryClass = $categoryClass;
        $this->postClass = $postClass;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'category_name'
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