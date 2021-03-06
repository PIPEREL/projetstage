<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class StudentFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mots', TextType::class, ['label'=>"recherche", "required"=>false])
            ->add('blackListed', CheckboxType::class, ['required' => false, 'mapped'=> false])
            ->add('status', ChoiceType::class, ['label'=>'status', 'mapped'=>false, 'required'=>false,'choices' =>['formation' => "formation", 'examens' => "examens", "diplomé" => "diplome" ],
            'expanded' => false,
            'multiple' => false,
        ])
            ->add('assigned', ChoiceType::class, ['label'=>'assigné', 'mapped'=>false, 'required'=>false,'choices' =>['oui' => true, 'non' => false ],
            'expanded' => false,
            'multiple' => false,
        ])
        ->add('filtrer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
