<?php

namespace App\Form;

use App\Entity\Affectation;
use App\Entity\Collaborateur;
use App\Entity\Fonction;
use App\Entity\Restaurant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchAffectationType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('collaborateur', EntityType::class, [
            'class' => Collaborateur::class,
            'required' => false,
            'label' => 'Collaborateur',
            'choice_label' => function (Collaborateur $collaborateur) {
                return $collaborateur->getNom() . ' ' . $collaborateur->getPrenom();
            },
            'placeholder' => '---',
            'attr' => [
                    'class' => 'large-12',
                ]
        ])
        ->add('poste', EntityType::class, [
            'class' => Fonction::class,
            'required' => false,
            'label' => 'Poste',
            'choice_label' => 'poste',
            'placeholder' => '---',
            'attr' => [
                    'class' => 'large-12',
                ]
        ])
        ->add('restaurant', EntityType::class, [
            'class' => Restaurant::class,
            'required' => false,
            'label' => 'Restaurant',
            'choice_label' => 'nom',
            'placeholder' => '--',
            'attr' => [
                    'class' => 'large-12',
                ]
        ])/*
        ->add('date_debut', DateType::class, [
            'required' => false,
            'widget' => 'single_text',
            'placeholder' => '---- / -- / --',
            'label' => 'Date de début',
            'attr' => [
                    'class' => 'large-12',
                ]
        ])
        ->add('date_fin', DateType::class, [
            'required' => false,
            'widget' => 'single_text',
            'placeholder' => '---- / -- / --',
            'label' => 'Date de fin',
            'attr' => [
                    'class' => 'large-12',
                ]
        ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Affectation::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
