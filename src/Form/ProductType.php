<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\MainColor;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du produit'
            ])
            ->add('description')
            ->add('mockupFront', FileType::class, [
                'mapped' => false,
                'required' => false,
                'help' => 'Upload de l\'image si ajout ou remplacement sinon laissez vide.',
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Merci d\uploader un fichier au format jpeg ou png',
                    ])
                ],
            ])
            ->add('mockupBack', FileType::class, [
                'mapped' => false,
                'required' => false,
                'help' => 'Upload de l\'image si ajout ou remplacement sinon laissez vide.',
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Merci d\uploader un fichier au format jpeg ou png',
                    ])
                ],
            ])
            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => false,
                'help' => 'Upload de l\'image si ajout ou remplacement sinon laissez vide.',
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Merci d\uploader un fichier au format jpeg ou png',
                    ])
                ],
            ])
            ->add('logo', FileType::class, [
                'mapped' => false,
                'required' => false,
                'help' => 'Upload de l\'image si ajout ou remplacement sinon laissez vide.',
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Merci d\uploader un fichier au format jpeg ou png',
                    ])
                ],
            ])
            ->add('status', ChoiceType::class,[
                'choices' => [
                    'Disponible' => 1,
                    'Non Disponible' => 2
                ],
                'label' => 'Disponibilité'
            ])
            ->add('mainColor', EntityType::class, [
                'class' => MainColor::class,
                'choice_label' => 'main_color_name',
                'multiple' => false,
                'expanded' => false
            ])
            // ->add('secondaryColor')
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'firstname',
                'multiple' => false,
                'placeholder' => 'Associer ce produit à un utilisateur ?',
                'expanded' => false,
                'help' => 'Laissez par défaut si pas d\'utilisateur associé'
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'help' => 'Sélectionnez au minimum une catégorie'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'attr' => [
                'novalidate' => 'novalidate',
            ]
        ]);
    }
}
