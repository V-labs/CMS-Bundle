<?php

namespace Vlabs\CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Vlabs\CmsBundle\Entity\Category;
use Vlabs\CmsBundle\Repository\CategoryRepository;

class CategoryTreeType extends AbstractType
{
    private $categoryClass;

    function __construct($categoryClass)
    {
        $this->categoryClass = $categoryClass;
    }

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

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'class' => $this->categoryClass,
            'property' => 'name',
            'query_builder' => function (CategoryRepository $r) {
                return $r->createQueryBuilder('c')
                    ->orderBy('c.position');
            }
        ]);
    }

    public function getParent()
    {
        return 'entity';
    }

    public function getName()
    {
        return 'category_tree';
    }
}
