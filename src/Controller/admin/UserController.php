<?php

namespace App\Controller\admin;

use DateTime;
use App\Entity\User;
use App\Entity\Intervenant;
use App\Form\IntervenantType;
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
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('admin/user/register', name: 'admin_intervenant_register')]
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder,  MailerInterface $mailer): Response
    {
        $user = new User();
        $intervenant = new Intervenant();
        $form = $this->createForm(IntervenantType::class, $user);
        $contact = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        

            // encode the plain password
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
            $intervenant->setCodeExam($form->get('codeExam')->getData());
            $intervenant->setPerstudent($form->get('perStudent')->getData());
            $user->setIntervenant($intervenant);
            
            if($form->get('sendEmail')->getData() == true){
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
            $entityManager->flush();
            return $this->redirectToRoute('user_admin');
        }

        return $this->render('admin/user/newIntervenant.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView()
        ]);
    }

  

}
