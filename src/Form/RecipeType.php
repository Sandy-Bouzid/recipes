<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Recipe;
use DateTimeImmutable;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Symfony\Component\Translation\t;

class RecipeType extends AbstractType
{
    public function __construct(private FormListenerFactory $formListenerFactory)
    {  
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'empty_data' => '',
                'label' => t('recipeForm.title')
            ])
            ->add('slug', TextType::class, [
                'required' => false,
                'label' => t('recipeForm.slug')
            ])
            ->add('thumbnailFile', FileType::class, [
                'required' => false,
                'label' => t('recipeForm.thumnailFile')
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label'=> 'name',
                'label' => t('recipeForm.category')
            ])
            ->add('content', TextareaType::class, [
                'empty_data' => '',
                'label' => t('recipeForm.content')
            ])
            ->add('duration', NumberType::class,[
                'label' => t('recipeForm.duration')
            ])
            ->add('quantities', CollectionType::class, [
                'label' => t('recipeForm.quantities'),
                'entry_type' => QuantityType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'entry_options' => ['label' => false],
                'attr' => [
                    'data-controller' => 'form-collection',
                    'data-form-collection-add-label-value' => 'Ajouter un ingrédient',
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => t('recipeForm.submit')
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->formListenerFactory->autoSlug('title'))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->formListenerFactory->timestamps())
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
