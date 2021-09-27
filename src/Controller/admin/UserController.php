<?php

namespace App\Controller\admin;

use DateTime;
use App\Entity\User;
use App\Entity\Intervenant;
use App\Form\IntervenantType;
use App\Repository\EventRepository;
use App\Repository\IntervenantRepository;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    #[Route('admin/user', name: 'user_admin')]
    public function index(IntervenantRepository $intervenantRepository): Response
    {// page d'affichage des professeurs/examinateurs 
        $examinateurs = $intervenantRepository->findExaminer();
        $formateurs = $intervenantRepository->findall();

        return $this->render('admin/user/index.html.twig', [
            'examinateurs' => $examinateurs,
            'formateurs' => $formateurs
        ]);
    }

    #[Route('admin/user/register', name: 'admin_intervenant_register')]
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder,  MailerInterface $mailer): Response
    {//ajout d'un professeur (on génère un compte utilisateur en même temps)
        $user = new User();
        $intervenant = new Intervenant();
        $form = $this->createForm(IntervenantType::class, $user);
        $contact = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        

            // génère un mot de passe
            $unique= md5(uniqid());
            $shuffled= substr(str_shuffle($unique),0,4);
            $plainpassword =str_shuffle(ucfirst($form->get('name')->getdata()).ucfirst($form->get('firstname')->getdata())).$shuffled;
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $plainpassword
                )
                );
            $user->setCreatedAt(new DateTime('NOW'));
            $intervenant->setDailyrate($form->get('dailyRate')->getData());
            $intervenant->setHalfDayRate($form->get('halfDayRate')->getData()); 
            if($form->get('perStudent')->getData() !== null){
                $intervenant->setCodeExam($form->get('codeExam')->getData());
                $intervenant->setPerstudent($form->get('perStudent')->getData());
                }
            $user->setIntervenant($intervenant);
            
            if($form->get('sendEmail')->getData() == true){ // envoi un mail
            $email = (new TemplatedEmail())
            ->from(new Address('bastienpiperel@gmail.com', 'NoReplyPhiliance')) //addresse a remplacer par celle de philiance
            ->to($user->getEmail())
            ->subject('Vos identifiants Philiance sont désormais disponibles! ')
            ->htmlTemplate('emails/registerIntervenant.html.twig')
            ->context(['identifiant'=> $user->getEmail() , "motDePasse" => $plainpassword]);
            $mailer->send($email);
            $this->addFlash('message', "votre email a bien été envoyé");
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            // $entityManager->persist($intervenant);
            $entityManager->flush();
            return $this->redirectToRoute('user_admin');
        }

        return $this->render('admin/user/newIntervenant.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView()
        ]);
    }

    #[Route('admin/user/edit/{id}', name: 'admin_intervenant_edit', methods: ['GET', 'POST'])]
    public function editIntervenant(Request $request, User $user, IntervenantRepository $intervenantRepository ,UserPasswordEncoderInterface $passwordEncoder): Response
    {//page d'édition des intervenants
       
        $intervenant = $intervenantRepository->findOneBy(['user' => $user]);
        $form = $this->createForm(IntervenantType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {    
            $intervenant->setDailyrate($form->get('dailyRate')->getData());
            $intervenant->setHalfDayRate($form->get('halfDayRate')->getData());
            $intervenant->setCodeExam($form->get('codeExam')->getData());
            $intervenant->setPerstudent($form->get('perStudent')->getData());
            
        
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            // $entityManager->persist($intervenant);
            $entityManager->flush();
            return $this->redirectToRoute('user_admin');
        }

        return $this->render('admin/user/editIntervenant.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    #[Route('admin/user/show/{id}', name: 'admin_user_show', methods: ['GET', 'POST'])]
    public function usershow(Intervenant $intervenant, EventRepository $eventRepository)
    {//page d'affichage du calendrier de l'utilisateur selectionné
        $protocol ="http";
        if(isset($_SERVER['HTTPS'])){
            $protocol ="https";
        }
        $serverName = $_SERVER['SERVER_NAME'];

        $baseurl = $_SERVER['REDIRECT_BASE'];

        $url= $protocol.'://' .$serverName. $baseurl.'/admin/event/';

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
