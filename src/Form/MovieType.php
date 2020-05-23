<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Movie;
use App\Entity\Person;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                
            ])
            ->add('releaseDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de sortie',
            ])
            ->add('director', EntityType::class, [
                'class' => Person::class,
                'choice_label' => 'name',
                'label' => 'Réalisateur',
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Catégories',
                'multiple' => true
            ])
            ->add('writers', EntityType::class, [
                'class' => Person::class,
                'choice_label' => 'name',
                'label' => 'Scénaristes',
                'multiple' => true
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Affiche',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '1024k',
                    ])
                ]

            ])
            ->add('MovieActors', CollectionType::class, [
                'label' => 'Acteurs',
                'entry_type' => MovieActorType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                "by_reference" => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
            'error_mapping' => [
                'movie[MovieActors][0][characterName]' => 'movieActors',
            ],
        ]);
    }
}
