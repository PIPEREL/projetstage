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
        $protocol ="http";
        if(isset($_SERVER['HTTPS'])){
            $protocol ="https";
        }
        $serverName = $_SERVER['SERVER_NAME'];

        $baseurl = $_SERVER['REDIRECT_BASE'];

        $url= $protocol.'://' .$serverName. $baseurl.'/admin/event/setintervenant/';

        $events = $eventRepository->calendarExFo();
        $rdvs=[];
        foreach($events as $event){
                $textcolor = $event->getTypeEvent()->getTextColor();
                $background = "#B22222";
                $border =  $event->getTypeEvent()->getBorderColor();
            $description = "cliquer ici pour ajouter un intervenant";
            if($event->getIntervenant() !== null){
                $description = $event->getIntervenant()->getUser()->getName();
                $background = "#006400";
            }
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'title' => $event->getTypeEvent()->getTitle(),
                'description' => $description,
                'backgroundColor' => $background,
                'borderColor' => $border,
                'url' => $url.$event->getId(),
                'textColor' => $textcolor,
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
