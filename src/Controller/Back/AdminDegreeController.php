<?php

namespace App\Controller\Back;

use App\Constant\MessageConstant;
use App\Entity\Degree;
use App\Controller\BaseController;
use App\Form\DegreeType;
use App\Repository\DegreeRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminDegreeController.
 * 
 * @Route("/admin/degrees")
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999gmail.com>
 */
class AdminDegreeController extends BaseController
{
    /** @var DegreeRepository */
    private DegreeRepository $degreeRepository;

    /**
     * AdminDegreeController constructor.
     *
     * @param EntityManagerInterface $em
     * @param DegreeRepository $degreeRepository
     */
    public function __construct(EntityManagerInterface $em, DegreeRepository $degreeRepository)
    {
        parent::__construct($em);
        $this->degreeRepository = $degreeRepository;
    }

    /**
     * Permet de lister tous les niveaux.
     * 
     * @Route("/{page<\d+>?1}", name="admin_degree_index", methods={"POST","GET"})
     *
     * @param integer $page
     * @param PaginationService $pagination
     * 
     * @return Response
     */
    public function index(int $page, PaginationService $pagination): Response
    {
        $pagination->setEntityClass(Degree::class)
            ->setLimit(6)
            ->setPage($page);

        return $this->render('admin/degree/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Permet de creer un niveau.
     * 
     * @Route("/new", name="admin_degree_new", methods={"POST","GET"})
     *
     * @param Request $request
     * 
     * @return Response
     */
    public function new(Request $request): Response
    {
        $degree = new Degree();
        $form = $this->createForm(DegreeType::class, $degree);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->save($degree)) {
                $this->addFlash(
                    MessageConstant::SUCCESS_TYPE,
                    "Le niveau {$degree->getName()} a bien ete cree !"
                );
            } else {
                $this->addFlash(
                    MessageConstant::ERROR_TYPE,
                    "Il y a une erreur lors de la creation !"
                );
            }
            return $this->redirectToRoute('admin_degree_index');
        }
        return $this->render('admin/degree/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier un niveau.
     * 
     * @Route("/{id}/edit", name="admin_degree_edit", methods={"POST","GET"})
     *
     * @param Degree $degree
     * @param Request $request
     * 
     * @return Response
     */
    public function edit(Degree $degree, Request $request): Response
    {
        $form = $this->createForm(DegreeType::class, $degree);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->save($degree)) {
                $this->addFlash(
                    MessageConstant::SUCCESS_TYPE,
                    "Le niveau {$degree->getName()} a bien ete modifie !"
                );
            } else {
                $this->addFlash(
                    MessageConstant::ERROR_TYPE,
                    "Il y a une erreur lors de la modification !"
                );
            }
            return $this->redirectToRoute('admin_degree_index');
        }
        return $this->render('admin/degree/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un niveau.
     * 
     * @Route("/{id}/delete", name="admin_degree_delete")
     *
     * @param Degree $degree
     * 
     * @return Response
     */
    public function delete(Degree $degree): Response
    {
        if ($this->remove($degree)) {
            $this->addFlash(
                MessageConstant::SUCCESS_TYPE,
                "Le niveau {$degree->getName()} a bien ete supprime !"
            );
        }
        return $this->redirectToRoute('admin_degree_index');
    }
}
