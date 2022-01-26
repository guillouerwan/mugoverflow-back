<?php

namespace App\Form;

use App\Entity\MainColor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class MainColorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mainHexa', TextType::class, [
                'label' => 'Code hexadécimal (#code)'
            ])
            ->add('mainColorName', TextType::class, [
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
            'data_class' => MainColor::class,
        ]);
    }
}
