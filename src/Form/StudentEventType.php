<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\StudentEvent;
use App\Repository\EventRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Twig\AppVariable;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class StudentEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $student= $options['data']->getStudent()->getstatus();
        $builder
            ->add('event', EntityType::class, ['class' => Event::class ,  'choice_label' => function (Event $event) {
                return  $event->getTypeEvent()->gettype() .' du '. $event->getstart()->format('Y-m-d') . ' au ' . $event->getEnd()->format('Y-m-d');
            }, 
            'query_builder' => function(EventRepository $eventRepository) use ($student){
                return $eventRepository->findevent($student);
            } 
            ])
            ->add('Envoyer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StudentEvent::class,
        ]);
    }
}
