<?php

namespace App\Controller\admin;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(EventRepository $eventRepository): Response
    {
        $event = $eventRepository->calendarExFo();
        $rdvs=[];
        foreach($event as $event){
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'title' => $event->getTypeEvent()->getTitle(),
                'backgroundColor' => "#ffffff",
                'borderColor' =>"#cd2323",
                'textColor' =>"#000000",
                'allDay' => $event->getAllDay()       
            ];
        }

        $data = json_encode($rdvs);


        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            "data" => $data
        ]);
    }
}
