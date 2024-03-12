<?php

namespace App\Form;

use App\Entity\Recipe;
use DateTimeImmutable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\AsciiSlugger;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('slug', TypeTextType::class, [
                'required' => false,
            ])
            ->add('content')
            ->add('duration')
            ->add('enregistrer', SubmitType::class)
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->autoSlug(...))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->autoDate(...))
        ;
    }

    public function autoSlug(PreSubmitEvent $event): void
    {
        // Récupérer les données
        $data = $event->getData();

        // Manipuler les données
        if (empty($data['slug'])) {
            $slugger = new AsciiSlugger();
            $data['slug'] = strtolower($slugger->slug(($data['title'])));

            // Injecter les nouvelles données
            $event->setData($data);
        }
    }

    public function autoDate(PostSubmitEvent $event): void
    {
        $data = $event->getData();

        if (!($data) instanceof Recipe) {
            return;
        }

        if (!$data->getId()) {
            $data->setCreatedAt(new DateTimeImmutable());
        }

        $data->setUpdatedAt(new DateTimeImmutable());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
