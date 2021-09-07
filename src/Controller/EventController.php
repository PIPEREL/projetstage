<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Form\UnavailabilityFormType;
use App\Repository\EventRepository;
use App\Repository\IntervenantRepository;
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

}

