<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Intervenant;
use App\Entity\TypeEvent;
use PhpParser\Node\Stmt\Label;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('typeEvent',  EntityType::class, [
                'required' => false, 'label' => "type d'evenement", 'class' => TypeEvent::class, 'choice_label' => 'title',
                'group_by' => function ($choice) {
                    return $choice->getType();
                },
            ])

            ->add('start', DateTimeType::class, ['date_widget' => 'single_text', 'label' => 'début'])
            ->add('end', DateTimeType::class, ['date_widget' => 'single_text', 'label' => 'fin'])
            ->add('maxcandidate', IntegerType::class, ['label' => "nombre de candidats maximum"])
            ->add('all_day')
            ->add('intervenant', EntityType::class, ['required' => false, 'label' => "intervenant", 'class' => Intervenant::class, 'choice_label' => function (Intervenant $intervenant) {
                return $intervenant->getUser()->getFirstname() . ' ' . $intervenant->getUser()->getName();
            }]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
