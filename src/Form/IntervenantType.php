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
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class IntervenantType extends AbstractType
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
            ->add('tarif', NumberType::class, ['mapped' =>false, 'label' => "tarif", 'attr'=>["placeholder"=> "tarif journée complète"], 'attr' => ["min" => 0, "step" => 0.01]])
            ->add('tarif2', NumberType::class, ["mapped"=> false, 'label' => "tarif demi-journée", 'attr'=>["placeholder"=> "tarif demi-journée"],'attr' => ["min" => 0, "step" => 0.01]])
            ->add('codeexam', TextType::class, ['mapped'=> false, 'label' => "code examinateur", 'attr'=>["placeholder"=> "code examinateur"]])
           
            ->add('perstudent', NumberType::class, ['mapped' =>false, 'label' => "tarif par etudiant", 'attr'=>["placeholder"=> "tarif par etudiant"], 'attr' => ["min" => 0, "step" => 0.01]])
           
            ->add('sendEmail', CheckboxType::class, [ "mapped" =>false, 'attr' => array('checked'   => 'checked'), 'label' => 'envoyer email contenant le mdp?','required' => false,])
            ->add('Envoyer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
