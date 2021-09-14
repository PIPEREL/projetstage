<?php

namespace App\Controller\admin;

use App\Entity\Event;
use App\Form\EventType;
use App\Entity\StudentEvent;
use App\Form\StudentEvent2Type;
use App\Repository\EventRepository;
use App\Form\EventSetIntervenantType;
use App\Repository\StudentRepository;
use App\Repository\IntervenantRepository;
use App\Repository\StudentEventRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    #[Route('admin/event', name: 'event_admin')]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('admin/event/index.html.twig', [
            'controller_name' => 'EventController',
            'events' => $eventRepository->findall(),
        ]);
    }

    #[Route('admin/event/setintervenant/{id}', name: 'set_intervenant', methods: ['GET', 'POST'])]
    public function intervenantset(Request $request, Event $event): Response
    {
        $form = $this->createForm(EventSetIntervenantType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('admin/event/intervenant.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }


    #[Route('admin/event/addstudent/{id}', name: 'event_add_student', methods: ['GET', 'POST'])]
    public function addstudent(Request $request, StudentEventRepository $studentEventRepository ,StudentRepository $studentRepository, Event $event): Response
    {

        $studentEvent = new StudentEvent;
        $studentEvent->setEvent($event);
        
        $form = $this->createForm(StudentEvent2Type::class, $studentEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager = $this->getDoctrine()->getManager();

            $students = $form->get('student')->getdata();
            foreach($students as $student){
                if($studentEventRepository->findOneBy(['student'=> $student , 'event'=> $event]) == null ){
                $studentEvent = new StudentEvent;
                $studentEvent->setEvent($event);
                $studentEvent->setNote(false);
                $studentToAdd = $studentRepository->findOneBy(['id'=>$student]);
                $studentEvent->setStudent($studentToAdd);
                $studentToAdd->setAssigned(true);
                $entityManager->persist($studentEvent);
                $entityManager->persist($studentToAdd);
                }
            }
            $entityManager->flush();
   
             return $this->redirectToRoute('event_show', ['id'=> $event->getId()]);
        }


        return $this->renderForm('admin/event/addstudent.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    
    #[Route('admin/event/new', name: 'event_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            
            if($form->get("all_day")->getdata() == true){
                $event->setEnd($event->getEnd()->setTime(23,59));
                $event->setStart($event->getStart()->setTime(00,00));
                }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('event_admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('admin/event/{id}', name: 'event_show', methods: ['GET','POST'])]
    public function show(Event $event, StudentEventRepository $studentEventRepository, StudentRepository $studentRepository, Request $request): Response
    {
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
   
           $studentevent = $studentEventRepository->findby(['event'=>$event]);
           $countstudent = count($studentevent);
        //    $studentevent = $studentEventRepository->findby(['event'=>$event, 'note'=> false]);

        return $this->render('admin/event/show.html.twig', [
            'event' => $event,
            'studentevents'=> $studentevent,
            'count' => $countstudent
        ]);
    }

    #[Route('admin/event/edit/{id}', name: 'event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('event_admin', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('admin/event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }



    #[Route('admin/event/delete/{id}', name: 'event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event): Response
    {
        if ($this->isCsrfTokenValid('delete' . $event->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('event_admin', [], Response::HTTP_SEE_OTHER);
    }

    


}
