<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\StudentEvent;
use App\Form\EventType;
use App\Form\UnavailabilityFormType;
use App\Repository\EventRepository;
use App\Repository\IntervenantRepository;
use App\Repository\StudentEventRepository;
use App\Repository\StudentRepository;
use App\Repository\TypeEventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/unavailability', name: 'user_unavailability', methods: ['GET','POST'])]
    public function new(Request $request, TypeEventRepository $typeEventRepository): Response
    {
        $event = new Event();
        $form = $this->createForm(UnavailabilityFormType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($form->get("all_day")->getdata() == true){
            $event->setEnd($event->getEnd()->setTime(23,59));
            $event->setStart($event->getStart()->setTime(00,00));
            }

            $event->setMaxcandidate(0);
            $event->setIntervenant($this->getUser()->getIntervenant());
            $event->setTypeEvent($typeEventRepository->findOneBy(['title'=>"indisponibilité"]));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('user', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('event/unavailability.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    
    #[Route('/{id}', name: 'user_event_show', methods: ['GET', 'POST'])]
    public function show(Event $event, StudentEventRepository $studentEventRepository, StudentRepository $studentRepository, Request $request): Response
    {
        if($event->getIntervenant() != $this->getUser()->getIntervenant())
        {
            return $this->redirectToRoute('user', [], Response::HTTP_SEE_OTHER);
        }


        if ($request->request->get('id') !== null) {
         $repo = $studentEventRepository->findOneBy(['id'=>$request->request->get('id')]);
         $student = $studentRepository->findOneBy(['id'=>$repo->getStudent()]);
         $repo->setNote(true);
         $student->setAssigned(false);
         if($request->request->get('validation') == 1){
            if($student->getstatus() == "formation"){
                $student->setStatus('examens');
            }else{
                $student->setStatus('diplome');
            }
         }
         $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($repo);
            $entityManager->persist($student);
            $entityManager->flush();
        }

        $studentevent = $studentEventRepository->findby(['event'=>$event, 'note'=> false]);
        
        return $this->render('event/show.html.twig', [
            'event' => $event,
            'studentevents'=> $studentevent
            
        ]);
    }

}

