<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Promo;
use App\Entity\Status;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('status', EntityType::class, [
                'class' => Status::class,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => false
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Utilisateur' => "ROLE_USER",
                    'Admin' => "ROLE_ADMIN",
                    'SuperAdmin' => "ROLE_SUPERADMIN",
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('promo', EntityType::class, [
                'class' => Promo::class,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => false
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
                // On récupère le form depuis l'event (pour travailler avec)
                $form = $event->getForm();
                // On récupère le user mappé sur le form depuis l'event
                $user = $event->getData();

                // On conditionne le champ "password"
                // Si user existant, il a id non null
                if ($user->getId() !== null) {
                    // Edit
                    $form->add('password', PasswordType::class, [
                        // Pour le form d'édition, on n'associe pas le password à l'entité
                        // @link https://symfony.com/doc/current/reference/forms/types/form.html#mapped
                        'mapped' => false,
                        'attr' => [
                            'placeholder' => 'Laissez vide si inchangé'
                        ]
                    ]);
                } else {
                    // New
                    $form->add('password', PasswordType::class, [
                        // En cas d'erreur du type
                        // Expected argument of type "string", "null" given at property path "password".
                        // (notamment à l'edit en cas de passage d'une valeur existante à vide)
                        'empty_data' => '',
                        // On déplace les contraintes de l'entité vers le form d'ajout
                        'constraints' => [
                            new NotBlank(),
                            new Regex(
                                "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/",
                                "Le mot de passe doit contenir au minimum 8 caractères, une majuscule, un chiffre et un caractère spécial"
                            ),
                        ],
                    ]);
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => [
                'novalidate' => 'novalidate',
            ]
        ]);
    }
}
