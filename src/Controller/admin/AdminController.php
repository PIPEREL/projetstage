<?php

namespace App\Controller\admin;

use DateTime;
use App\Entity\User;
use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(EventRepository $eventRepository): Response
    {//affiche le calendrier général coté administrateurs
        $protocol ="http";
        if(isset($_SERVER['HTTPS'])){
            $protocol ="https";
        }
        $serverName = $_SERVER['SERVER_NAME'];

        $baseurl = $_SERVER['REDIRECT_BASE'];

        $url= $protocol.'://' .$serverName. $baseurl.'/admin/event/'; // crée une url de redirection pour le javascript


        $events = $eventRepository->calendarExFo(); // recupere les event qui ne sont pas des indisponibilités
        $rdvs=[];
        foreach($events as $event){ // gère l'apparence et les données passés au javascript
                $numberOfStudent = count($event->getStudentEvents()); 
                $textcolor = $event->getTypeEvent()->getTextColor();
                $background = "#B22222";
                $border =  $event->getTypeEvent()->getBorderColor();


            $description = "pas d'intervenant " ." | ".$numberOfStudent."/".$event->getMaxcandidate()." candidats";


            if($event->getIntervenant() !== null){
                $description = $event->getIntervenant()->getUser()->getName()." | ".$numberOfStudent."/".$event->getMaxcandidate()." candidats";
                $background = "#006400"; // $event->gettypeevent->getbagroundcolor();
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

        $data = json_encode($rdvs); // transforme le tableau en json pour réaliser le passage


        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            "data" => $data
        ]);
    }
}
