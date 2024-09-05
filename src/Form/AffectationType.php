<?php

namespace App\Form;

use App\Entity\Affectation;
use App\Entity\Collaborateur;
use App\Entity\Fonction;
use App\Entity\Restaurant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AffectationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_debut', null, [
                'widget' => 'single_text',
            ])
            ->add('date_fin', null, [
                'widget' => 'single_text',
            ])
            ->add('poste', EntityType::class, [
                'class' => Fonction::class,
                'choice_label' => 'poste',
            ])
            ->add('collaborateur', EntityType::class, [
                'class' => Collaborateur::class,
                'choice_label' => function (Collaborateur $collaborateur) {
                    return $collaborateur->getNom() . ' ' . $collaborateur->getPrenom();
                },
            ])
            ->add('restaurant', EntityType::class, [
                'class' => Restaurant::class,
                'choice_label' => 'nom',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Affectation::class,
        ]);
    }
}
