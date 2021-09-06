<?php

namespace App\Controller\admin;

use App\Entity\Event;
use App\Form\EventSetIntervenantType;
use App\Repository\IntervenantRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    #[Route('admin/event', name: 'event_admin')]
    public function index(): Response
    {
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
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


}
