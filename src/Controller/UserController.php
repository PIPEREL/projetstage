<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/user', name: 'user')]
    public function index(EventRepository $eventRepository): Response
    {
           // if ($this->getUser() == null) {
        //     return $this->redirectToRoute('app_login');
        // }

        $protocol ="http";
        if(isset($_SERVER['HTTPS'])){
            $protocol ="https";
        }
        $serverName = $_SERVER['SERVER_NAME'];

        $baseurl = $_SERVER['REDIRECT_BASE'];

        $url= $protocol.'://' .$serverName. $baseurl.'/event/edit/';

        $intervenant = $this->getUser()->getIntervenant();        
        $events = $eventRepository->calendarUser($intervenant);
        $rdvs=[];
        foreach($events as $event){
                $textcolor = "#8B0000";
                $background = "#808080";
                $border = "#006400";
                $description = false;
            if($event->getTypeEvent()->getType() !== "dispo"){
                $description = false;
                $textcolor = "#ffffff";
                $background = "#7CFC00";
                $border = "#006400";
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
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'data' => $data
        ]);
    }
}

//SELECT * from event where intervenant_id = intervenant.id && $start_one <= $end_two && $end_one >= $start_two)

//SELECT * From intervenant where id not in (select intervenant_id from event inner join intervenant where intervenant.id = event.intervenant_id)

//SELECT * From intervenant where id not in (select intervenant_id from event inner join intervenant where intervenant.id = event.intervenant_id AND event.start <= '2022-09-19 23:00:00' && event.end >= "2022-09-19 00:00:00");
