<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('gender', ChoiceType::class, ['label' => "civilité",'choices' =>['Homme' => "H", 'Femme' => "F", "Autre" => "O" ],
        'expanded' => true,
        'multiple' => false,
    ])
        ->add('name', TextType::class, ['label' => "nom", 'attr'=>["placeholder"=> "nom"]])
        ->add('firstname', TextType::class,['label' => "prénom", 'attr'=>["placeholder"=> "prenom"]])
        ->add('address', TextType::class, ['label' => "addresse", 'attr'=>["placeholder"=> "adresse"]])
        ->add('email', EmailType::class, ['label' => "email", 'attr'=>["placeholder"=> "email"]])
        ->add('phone', TextType::class, ['label' => "téléphone", 'attr'=>["placeholder"=> "numéro de téléphone"],'constraints' => [new Regex('#^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4}|\d{2}(?:[\s.-]?\d{3}){2})$#')]])
        ->add(
            'roles', ChoiceType::class, [
                'choices' => ['SUPER ADMIN' => 'ROLE_SUPERADMIN','ADMIN' => 'ROLE_ADMIN'],
                'expanded' => true,
                'multiple' => true,
            ]
        )
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'mapped' => false,
            'invalid_message' => 'The password fields must match.',
            'options' => ['attr' => ['class' => 'password-field']],
            'required' => true,
            'first_options'  => ['label' => 'Password'],
            'second_options' => ['label' => 'Repeat Password'],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
