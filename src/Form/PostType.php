<?php

namespace App\Form;

use App\Entity\Movie;
use App\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                "label" => "Titre",
                'choice_label' => 'title',
            ])
            ->add('content', TextType::class, [
                "label" => "Contenu",
                'choice_label' => 'content',
            ])
            ->add('movies', EntityType::class, [
                'class' => Movie::class,
                'choice_label' => 'title',
                'multiple' => true,
                "label" => "Films",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
