<?php

namespace App\Controller\admin;

use App\Entity\StudentEvent;
use App\Form\StudentEvent1Type;
use App\Repository\StudentEventRepository;
use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/student/event')]
class StudentEventController extends AbstractController
{

    #[Route('/{id}', name: 'student_event_delete', methods: ['POST'])]
    public function delete(Request $request, StudentEvent $studentEvent, StudentRepository $studentRepository): Response

    { // gère le retrait d'évenement.

        if ($this->isCsrfTokenValid('delete'.$studentEvent->getId(), $request->request->get('_token'))) {

            $entityManager = $this->getDoctrine()->getManager();
            $student = $studentRepository->findOneBy(['id'=>$studentEvent->getStudent()]);

            if($request->request->get('assigned') == true){    // si la case 'retirer l'assignation' est cochée, on retire l'assignation 

                $student->setAssigned(false);
                $entityManager->persist($student);
            }
            $entityManager->remove($studentEvent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('student_show', ['id'=> $student->getId()]);
    }

}
