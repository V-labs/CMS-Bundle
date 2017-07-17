<?php

namespace Vlabs\CmsBundle\Form;

use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\Translator;
use Vlabs\CmsBundle\Entity\Category;
use Vlabs\CmsBundle\Repository\CategoryRepository;

class CategoryParentTreeType extends AbstractType implements TranslationContainerInterface
{
    private $translator;
    private $categoryClass;

    function __construct(Translator $translator, $categoryClass)
    {
        $this->translator = $translator;
        $this->categoryClass = $categoryClass;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        /** @var Category $eCategory */
        $eCategory = $view->parent->vars['value'];

        $aCategories = [];
        $rootCategories = [];
        foreach ($view->vars['choices'] as $choice) {
            $category = $choice->data;
            $aCategories[] = $category;
            if (is_null($category->getParent())) {
                $rootCategories[] = $category;
            }
        }

        $view->vars['choices'] = $this->buildTreeChoices(
            $eCategory,
            $view->vars['data'],
            $aCategories,
            $rootCategories
        );
    }

    private function buildTreeChoices($eCategory, $sCategory, $aCategories, $categories, $level = 0)
    {
        $result = array();

        if (!$level) {
            $result[] = new ChoiceView(
                null,
                ' ',
                $this->translator->trans('root', [], 'vlabs_cms'),
                [ 'selected' => $sCategory == null ]
            );
        }

        /** @var Category $category */
        foreach ($categories as $category) {

            if ($eCategory == $category) continue;

            $result[] = new ChoiceView(
                $category,
                $category->getId(),
                str_repeat(' ', $level) . '└ ' . $category->getName(),
                [ 'selected' => $sCategory == $category ]
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
                        $eCategory,
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => $this->categoryClass,
            'query_builder' => function (CategoryRepository $r) {
                return $r->createQueryBuilder('c')
                    ->orderBy('c.position');
            },
            'translation_domain' => 'vlabs_cms',
            'choice_label' => 'name'
        ]);
    }

    public function getParent()
    {
        return EntityType::class;
    }

    public function getName()
    {
        return 'category_parent_tree';
    }

    static function getTranslationMessages()
    {
        return [
            new Message('root', 'vlabs_cms')
        ];
    }
}
