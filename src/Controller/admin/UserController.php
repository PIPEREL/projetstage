<?php

namespace App\Controller\admin;

use DateTime;
use App\Entity\User;
use App\Form\IntervenantType;
use Symfony\Component\HttpFoundation\Request;
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
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(IntervenantType::class, $user);
        $form->handleRequest($request);

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
            dd($user);
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

    // $email = (new TemplatedEmail())
    //         ->from($contact->get('email')->getData())
    //         ->to($annonce->getUsers()->getEmail())
    //         ->subject('contact au sujet de votre annonce')
    //         ->htmlTemplate('emails/contact_annonce.html.twig')
    //         ->context(['annonce'=> $annonce, "mail" => $contact->get('email')->getData(), 'message' => $contact->get('message')->getData()]);
    //         $mailer->send($email);
    //         $this->addFlash('message', "votre email a bien été envoyé");

}
