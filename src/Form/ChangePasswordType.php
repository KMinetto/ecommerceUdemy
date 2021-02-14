<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('firstname', TextType::class, [
                'disabled' => true,
                'label' => 'Prénom'
            ])
            ->add('lastname', TextType::class, [
                'disabled' => true,
                'label' => 'Nom'
            ])
            ->add('email', EmailType::class, [
                'disabled' => true,
                'label' => 'Email'
            ])
            ->add('old_password', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Mot de passe actuel'
                ]
            ])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identique.',
                'label' => 'Nouveau mot de passe',
                'required' => true,
                'first_options' => [
                    'label' => 'Nouveau mot de passe',
                    'attr' => [
                        'placeholder' => 'Votre nouveau mot de passe'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmation du nouveau mot de passe',
                    'attr' => [
                        'placeholder' => 'Confirmer votre nouveau mot de passe'
                    ]
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Changer mon mot de passe'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
