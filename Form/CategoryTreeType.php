<?php

namespace Vlabs\CmsBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Vlabs\CmsBundle\Entity\Category;
use Vlabs\CmsBundle\Repository\CategoryRepository;

/**
 * Class CategoryTreeType
 * @package Vlabs\CmsBundle\Form
 */
class CategoryTreeType extends AbstractType
{
    private $categoryClass;

    /**
     * CategoryTreeType constructor.
     * @param $categoryClass
     */
    function __construct($categoryClass)
    {
        $this->categoryClass = $categoryClass;
    }

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $allCategories = [];
        $rootCategories = [];
        foreach ($view->vars['choices'] as $choice) {
            /** @var Category $category */
            $category = $choice->data;
            $allCategories[] = $category;
            if (is_null($category->getParent())) {
                $rootCategories[] = $category;
            }
        }
        $view->vars['choices'] = $this->buildTreeChoices(
            $view->vars['data'],
            $allCategories,
            $rootCategories
        );
    }

    /**
     * @param $sCategory
     * @param $aCategories
     * @param $categories
     * @param int $level
     * @return array
     */
    private function buildTreeChoices($sCategory, $aCategories, $categories, $level = 0)
    {
        $result = array();
        /** @var Category $category */
        foreach ($categories as $category) {
            $result[] = new ChoiceView(
                $category,
                $category->getId(),
                str_repeat(' ', $level) . ($level ? '└' : '') . ' ' . $category->getName(),
                [
                    'selected' => $sCategory == $category
                ]
            );
            $chCategories = [];
            /** @var Category $chCategory */
            foreach ($aCategories as $chCategory) {
                if ($chCategory->getParent() == $category) {
                    $chCategories[] = $chCategory;
                }
            }
            if (count($chCategories)) {
                $result = array_merge(
                    $result,
                    $this->buildTreeChoices(
                        $sCategory,
                        $aCategories,
                        $chCategories,
                        $level + 1
                    )
                );
            }
        }

        return $result;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => $this->categoryClass,
            'choice_label' => 'name',
            'query_builder' => function (CategoryRepository $r) {
                return $r->createQueryBuilder('c')
                    ->orderBy('c.position');
            }
        ]);
    }

    /**
     * @return null|string
     */
    public function getParent()
    {
        return EntityType::class;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'category_tree';
    }
}