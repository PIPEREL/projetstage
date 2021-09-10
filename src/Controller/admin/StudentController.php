<?php

namespace App\Controller\admin;

use DateTime;
use App\Entity\Student;
use App\Entity\StudentEvent;
use App\Form\StudentType;
use App\Form\StudentEditType;
use App\Form\StudentEventType;
use App\Form\StudentFilterType;
use App\Repository\StudentEventRepository;
use App\Repository\StudentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('admin/student')]
class StudentController extends AbstractController
{
    #[Route('/', name: 'student_index', methods: ['GET','POST'])]
    public function index(StudentRepository $studentRepository, Request $request): Response
    {
        $students = $studentRepository->findBy(['blackListed'=>false]);

        $form = $this->createForm(StudentFilterType::class);
        $filter = $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $students = $studentRepository->filterstudent($filter->get('mots')->getdata(), $filter->get('blackListed')->getdata(), $filter->get('status')->getData(), $filter->get('assigned')->getData());
        }
        return $this->render('student/index.html.twig', [
            'students' => $students,
            'form' => $form->createView()
        ]);
    }

    #[Route('/new', name: 'student_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $student->setTcf($form->get('tcf')->getdata()->getTitle());
            if($form->get("status")->getdata() == true){
            $student->setStatus("formation");
            }else{
            $student->setStatus('examens');
            }
            $student->setBlackListed(false);
            $student->setAssigned(false);
            $student->setCreatedAt(new DateTime('NOW'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($student);
            $entityManager->flush();

            return $this->redirectToRoute('student_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('student/new.html.twig', [
            'student' => $student,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'student_show', methods: ['GET', 'POST'])]
    public function show(Student $student, StudentEventRepository $studentEventRepository, Request $request): Response
    {
        $studentevent = new StudentEvent;
        $studentevent->setStudent($student);
        $form = $this->createForm(StudentEventType::class, $studentevent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($studentEventRepository->findOneBy(['student'=> $studentevent->getStudent() , 'event'=> $studentevent->getEvent()]) == null ){
            $studentevent->setNote(false);    
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($studentevent);
            $entityManager->flush();
            $student->setAssigned(true);
            $entityManager->persist($student);
            $entityManager->flush();
            return $this->redirectToRoute('student_show', ['id'=> $student->getId()]);
            }else{

            $this->addFlash('message', "cet étudiant a déjà été inscrit à cette formation.");
            return $this->redirectToRoute('student_show', ['id'=> $student->getId()]);
            }
            
        }

        return $this->render('student/show.html.twig', [
            'student' => $student,
            'form' => $form->createView(),
            'stevents' => $student->getStudentEvents()
        ]);
    }

    #[Route('/{id}/edit', name: 'student_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Student $student): Response
    {
        $form = $this->createForm(StudentEditType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('student_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('student/edit.html.twig', [
            'student' => $student,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'student_delete', methods: ['POST'])]
    public function delete(Request $request, Student $student): Response
    {
        if ($this->isCsrfTokenValid('delete'.$student->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($student);
            $entityManager->flush();
        }

        return $this->redirectToRoute('student_index', [], Response::HTTP_SEE_OTHER);
    }
}
