<?php

namespace App\Controller\Back;

use App\Constant\MessageConstant;
use App\Controller\BaseController;
use App\Entity\Professor;
use App\Form\ProfessorType;
use App\Repository\ProfessorRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminProfessorController.
 * 
 * @Route("/admin/professors")
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class AdminProfessorController extends BaseController
{
    /** @var ProfessorRepository */
    private ProfessorRepository $professorRepository;

    /**
     * AdminProfessorController constructeur.
     *
     * @param EntityManagerInterface $em
     * @param ProfessorRepository $professorRepository
     */
    public function __construct(EntityManagerInterface $em, ProfessorRepository $professorRepository)
    {
        parent::__construct($em);
        $this->professorRepository = $professorRepository;
    }

    /**
     * Permet de lister les professeurs.
     * 
     * @Route("/{page<\d+>?1}", name="admin_professor_index", methods={"POST","GET"})
     *
     * @param integer $page
     * @param PaginationService $pagination
     * 
     * @return Response
     */
    public function index(int $page, PaginationService $pagination): Response
    {
        $pagination->setEntityClass(Professor::class)
            ->setLimit(6)
            ->setPage($page);

        return $this->render('admin/professor/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Permet de creer un profil professeur.
     * 
     * @Route("/new", name="admin_professor_new", methods={"POST","GET"})
     *
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $professor = new Professor();
        $form = $this->createForm(ProfessorType::class, $professor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            $this->uploadFile($file, $professor);

            if ($this->save($professor)) {
                $this->addFlash(
                    MessageConstant::SUCCESS_TYPE,
                    "The professor profile {$professor->getFirstName()} is created successfully !"
                );
            } else {
                $this->addFlash(
                    MessageConstant::ERROR_TYPE,
                    "Error during the creation !"
                );
            }
            return $this->redirectToRoute('admin_professor_index');
        }
        return $this->render('admin/professor/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier un profil professeur.
     * 
     * @Route("/{id}/edit", name="admin_professor_edit", methods={"POST","GET"})
     *
     * @param Request $request
     * @param Professor $professor
     * 
     * @return Response
     */
    public function edit(Professor $professor, Request $request): Response
    {
        $form = $this->createForm(ProfessorType::class, $professor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            $this->uploadFile($file, $professor);

            if ($this->save($professor)) {
                $this->addFlash(
                    MessageConstant::SUCCESS_TYPE,
                    "The professor profile {$professor->getFirstName()} is updated successfully !"
                );
            } else {
                $this->addFlash(
                    MessageConstant::ERROR_TYPE,
                    "Error during the modification !"
                );
            }
            return $this->redirectToRoute('admin_professor_index');
        }
        return $this->render('admin/professor/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un profil professeur.
     * 
     * @Route("/{id}/delete", name="admin_professor_delete")
     *
     * @param Professor $professor
     * @return Response
     */
    public function delete(Professor $professor): Response
    {
        if ($this->remove($professor)) {
            $this->addFlash(
                MessageConstant::SUCCESS_TYPE,
                "Professor {$professor->getFirstName()} removed successfully !"
            );
        }
        return $this->redirectToRoute('admin_professor_index');
    }
}
