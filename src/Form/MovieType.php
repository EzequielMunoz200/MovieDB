<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Movie;
use App\Entity\Person;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                "label" => "Titre",
            ])
            /* ->add('posterFilename') */
            ->add('releaseDate', DateType::class, [
                'widget' => 'single_text',
                "label" => "Date de sortie",

            ])
            ->add('director', EntityType::class, [
                'class' => Person::class,
                'choice_label' => 'name',
                "label" => "Réalisateur",
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                "label" => "Catégories",
                "multiple" => true
            ])
            ->add('writers', EntityType::class, [
                'class' => Person::class,
                'choice_label' => 'name',
                "label" => "Scénaristes",
                "multiple" => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
