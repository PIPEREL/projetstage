<?php

namespace App\Controller\superAdmin;

use DateTime;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SuperAdminController extends AbstractController
{
    #[Route('/super/admin', name: 'super_admin')]
    public function index(UserRepository $userRepository): Response
    {
        $admin =$userRepository->findbyrole();
        return $this->render('super_admin/index.html.twig', [
            'users'=> $admin,

        ]);
    }
    
    #[Route('super/admin/register', name: 'admin_register')]
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $contact = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setCreatedAt(new DateTime('NOW'));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('super_admin');
        }

        return $this->render('super_admin/register.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

}
