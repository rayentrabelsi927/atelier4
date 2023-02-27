<?php

namespace App\Controller;
use App\Entity\Classroom;

use App\Repository\ClassroomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use App\Entity\Student;
use App\Form\ClassroomType;
use App\Form\SearchStudentByClassType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ClassroomController extends AbstractController
{
    #[Route('/classroom', name: 'app_classroom')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repo = $doctrine->getRepository(Classroom::class);
        $classrooms=$repo->findAll();

        $classrooms = $repo->findAll();
        return $this->render('classroom/affich.html.twig', [
            'controller_name' => 'ClassroomController',
            'classrooms' => $classrooms
        ]);
    }

    #[Route('/classroomto/{id}', name: 'delete_classroom')]

    public function delete_classroom(int $id,ClassroomRepository $repo, ManagerRegistry $doctrine ):Response{
    $classroom=$doctrine->getRepository(Classroom::class)->find($id);
    $em=$doctrine->getManager();

    $em->remove($classroom);
 
    $em->flush();
    return$this->redirectToRoute("app_classroom");

}
 #[Route("/updateClassroom/{id}", name:"updateClassroom")]
     
    public function updateClassroom(Request $request,$id)
    {
        $classroom = $this->getDoctrine()->getRepository(Classroom::class)->find($id);
        $form = $this->createForm(Classroom::class, $classroom);
        $form->add('modifier',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('affich');
        }
        return $this->render("classroom/add.html.twig",['form'=>$form->createView()]);
    }

/**
     * @Route("/addClassroom", name="addClassroom")
     */
    public function addClassroom(Request $request)
    {
        $classroom = new Classroom();
        $form = $this->createForm(ClassroomType::class, $classroom);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($classroom);
            $em->flush();
            return $this->redirectToRoute('listClassroom');
        }
        return $this->render("classroom/add.html.twig",array('form'=>$form->createView()));
    }


}
