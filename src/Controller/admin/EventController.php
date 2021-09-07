<?php

namespace App\Controller\admin;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Form\EventSetIntervenantType;
use App\Repository\IntervenantRepository;
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

    
    #[Route('admin/event/new', name: 'event_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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

    #[Route('admin/event/{id}', name: 'event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('admin/event/show.html.twig', [
            'event' => $event,
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
