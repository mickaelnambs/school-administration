<?php

namespace App\Controller\Back;

use App\Constant\MessageConstant;
use App\Controller\BaseController;
use App\Entity\Student;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminStudentController.
 * 
 * @Route("/admin/students")
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class AdminStudentController extends BaseController
{
    /** @var StudentRepository */
    private StudentRepository $studentRepository;

    /**
     * AdminStudentController constructor.
     *
     * @param EntityManagerInterface $em
     * @param StudentRepository $studentRepository
     */
    public function __construct(EntityManagerInterface $em, StudentRepository $studentRepository)
    {
        parent::__construct($em);
        $this->studentRepository = $studentRepository;
    }

    /**
     * Permet de lister tous les etudiants.
     * 
     * @Route("/{page<\d+>?1}", name="admin_student_index", methods={"POST","GET"})
     *
     * @param integer $page
     * @param PaginationService $pagination
     * 
     * @return Response
     */
    public function index(int $page, PaginationService $pagination): Response
    {
        $pagination->setEntityClass(Student::class)
            ->setLimit(6)
            ->setPage($page);

        return $this->render('admin/student/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Permet d'enregistrer un etudiant.
     * 
     * @Route("/new", name="admin_student_new", methods={"POST","GET"})
     *
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            $this->uploadFile($file, $student);

            if ($this->save($student)) {
                $this->addFlash(
                    MessageConstant::class,
                    "L'etudiant {$student->getFirstName()} a bien ete inscrit !"
                );
            } else {
                $this->addFlash(
                    MessageConstant::class,
                    "Il y a une erreur pendant l'inscription !"
                );
            }
            return $this->redirectToRoute('admin_student_index');
        }
        return $this->render('admin/student/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier le profil d'un etudiant.
     * 
     * @Route("/{id}/edit", name="admin_student_edit", methods={"POST","GET"})
     *
     * @param Request $request
     * @param Student $student
     * 
     * @return Response
     */
    public function edit(Student $student, Request $request): Response
    {
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            $this->uploadFile($file, $student);

            if ($this->save($student)) {
                $this->addFlash(
                    MessageConstant::class,
                    "Le profil de {$student->getFirstName()} a bien ete modifie !"
                );
            } else {
                $this->addFlash(
                    MessageConstant::class,
                    "Il y a une erreur pendant la modification du profil !"
                );
            }
            return $this->redirectToRoute('admin_student_index');
        }
        return $this->render('admin/student/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un profil.
     * 
     * @Route("/{id}/delete", name="admin_student_delete")
     *
     * @param Student $student
     * 
     * @return Response
     */
    public function delete(Student $student): Response
    {
        if ($this->remove($student)) {
            $this->addFlash(
                MessageConstant::class,
                "Le profil de {$student->getFirstName()} a bien ete supprime !"
            );
        }
        return $this->redirectToRoute('admin_student_index');
    }
}
