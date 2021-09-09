<?php

namespace App\Form;

use App\Entity\Student;
use App\Entity\TypeEvent;
use App\Repository\TypeEventRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;

class StudentEditType extends AbstractType
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
        ->add('nativeCountry', CountryType::class,['label' => "Pays de naissance", 'attr'=>["placeholder"=> "Pays de naissance"]])
        ->add('nationality', TextType::class,['label' => "Nationalité", 'attr'=>["placeholder"=> "nationality"]])
        ->add('usualLanguage', LanguageType::class,['label' => "language d'usage", 'attr'=>["placeholder"=> "Langue d'usage"]])
        ->add('nativeLanguage', LanguageType::class,['label' => "langue native", 'attr'=>["placeholder"=> "Langue native"]])
        ->add('phone', TelType::class)
        ->add('mobilePhone', TelType::class)
        ->add('email', EmailType::class, ['label' => "email", 'attr'=>["placeholder"=> "email"]])
        ->add('birthday', DateType::class)
        ->add('tcf', EntityType::class, ['label' => "diplome désirée", 'mapped'=>false, 'class'=>TypeEvent::class, 'choice_label' => 'title',
            'query_builder' => function (TypeEventRepository $TypeEventRepository){
                return $TypeEventRepository->createQueryBuilder('test')
                ->where('test.type = :value')
                ->setParameter('value', 'examens');
            },
           
            'choice_value'=> 'title'
            ])
            ->add('status', ChoiceType::class, ['label'=>'status','choices' =>['formation' => "formation", 'examens' => "examens", "diplomé" => "diplome" ],
            'expanded' => false,
            'multiple' => false,
        ])
            ->add('blackListed', CheckboxType::class, ['required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
