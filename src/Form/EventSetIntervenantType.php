<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\TypeEvent;
use App\Entity\Intervenant;
use PhpParser\Node\Stmt\Label;
use App\Repository\EventRepository;
use Symfony\Component\Form\AbstractType;
use App\Repository\IntervenantRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class EventSetIntervenantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $event = $options['data'];
        $builder
            ->add('intervenant', EntityType::class, ['required' => false, 'label' => "intervenant", 'class' => Intervenant::class, 
            'query_builder' => function (IntervenantRepository $intervenantRepository) use ($event) {
                return $intervenantRepository->findavailable($event->getStart(), $event->getEnd());
            },
            'choice_label' => function (Intervenant $intervenant) {
                return $intervenant->getUser()->getName() . ' ' . $intervenant->getUser()->getFirstname();
            }]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
