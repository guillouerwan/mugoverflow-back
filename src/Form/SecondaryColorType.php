<?php

namespace App\Form;

use App\Entity\SecondaryColor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SecondaryColorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('secondaryHexa', TextType::class, [
                'label' => 'Code hexadécimal (#code)',
                'help' => 'Exemple : #021E00'
            ])
            ->add('secondaryColorName', TextType::class, [
                'label' => 'Nom de la couleur',
            ])
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'Disponible' => 1,
                    'Non disponible' => 2,
                ],
                'label' => 'Disponibilité'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SecondaryColor::class,
            'attr' => [
                'novalidate' => 'novalidate',
            ]
        ]);
    }
}
