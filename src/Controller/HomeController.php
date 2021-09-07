<?php

namespace App\Controller;

use App\Entity\Intervenant;
use App\Repository\IntervenantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(IntervenantRepository $intervenantRepository): Response
    {
        // if ($this->getUser() == null) {
        //     return $this->redirectToRoute('app_login');
        // }
       
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
