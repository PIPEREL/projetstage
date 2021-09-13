<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Student;
use App\Entity\StudentEvent;
use App\Repository\EventRepository;
use App\Repository\StudentRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Twig\AppVariable;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class StudentEvent2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $event= $options['data']->getEvent();
        $builder
            ->add('student', EntityType::class, ['class' => Student::class ,  'choice_label' => function (student $student) {
                return  $student->getname() . " ".  $student->getfirstname();
            },
            'query_builder' => function(StudentRepository $studentRepository) use ($event){
                return $studentRepository->findstudent($event->getTypeEvent()->getTitle(), $event->getTypeEvent()->getType());
            },
            "multiple" => true,
            "mapped" => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StudentEvent::class,
        ]);
    }
}
