<?php

namespace App\Controller\admin;

use App\Entity\TypeEvent;
use App\Form\TypeEventType;
use App\Repository\TypeEventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/type/event')]
class TypeEventController extends AbstractController
{
    #[Route('/', name: 'type_event_index', methods: ['GET'])]
    public function index(TypeEventRepository $typeEventRepository): Response
    {//page de gestion des types d'évenements
        return $this->render('admin/type_event/index.html.twig', [
            'type_events' => $typeEventRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'type_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    { //page d'ajout de type d'event
        $typeEvent = new TypeEvent();
        $form = $this->createForm(TypeEventType::class, $typeEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($typeEvent);
            $entityManager->flush();

            return $this->redirectToRoute('type_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/type_event/new.html.twig', [
            'type_event' => $typeEvent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'type_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypeEvent $typeEvent): Response
    { //page d'édition d'un type d'event
        $form = $this->createForm(TypeEventType::class, $typeEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('type_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/type_event/edit.html.twig', [
            'type_event' => $typeEvent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'type_event_delete', methods: ['POST'])]
    public function delete(Request $request, TypeEvent $typeEvent): Response
    { // page de suppression d'un type d'event (non recommandé)
        if ($this->isCsrfTokenValid('delete'.$typeEvent->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($typeEvent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('type_event_index', [], Response::HTTP_SEE_OTHER);
    }
}
