<?php

namespace App\Controller\Back;

use App\Entity\Branch;
use App\Form\BranchType;
use App\Constant\MessageConstant;
use App\Controller\BaseController;
use App\Repository\BranchRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminBranchController.
 * 
 * @Route("/admin/branchs")
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class AdminBranchController extends BaseController
{
    /** @var BranchRepository */
    private  BranchRepository $brachRepository;

    /**
     * AdminBranchController constructor.
     *
     * @param EntityManagerInterface $em
     * @param BranchRepository $brachRepository
     */
    public function __construct(EntityManagerInterface $em, BranchRepository $brachRepository)
    {
        parent::__construct($em);
        $this->brachRepository = $brachRepository;
    }

    /**
     * Permet de lister toutes les branches.
     * 
     * @Route("/{page<\d+>?1}", name="admin_branch_index", methods={"POST","GET"})
     *
     * @param int $page
     * @param PaginationService $pagination
     * @return Response
     */
    public function index(int $page, PaginationService $pagination): Response
    {
        $pagination->setEntityClass(Branch::class)
            ->setLimit(6)
            ->setPage($page);

        return $this->render('admin/branch/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Permet de creer une nouvelle branche.
     * 
     * @Route("/new", name="admin_branch_new", methods={"POST","GET"})
     *
     * @param Request $request
     * 
     * @return Response
     */
    public function new(Request $request): Response
    {
        $branch = new Branch();
        $form = $this->createForm(BranchType::class, $branch);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->save($branch)) {
                $this->addFlash(
                    MessageConstant::SUCCESS_TYPE,
                    "La branche {$branch->getName()} a bien ete creee"
                );
            } else {
                $this->addFlash(
                    MessageConstant::ERROR_TYPE,
                    "Il y a une erreur pendant la creation de la branche !"
                );
            }
            return $this->redirectToRoute('admin_branch_index');
        }
        return $this->render('admin/branch/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier une branche.
     * 
     * @Route("/{id}/edit", name="admin_branch_edit", methods={"POST","GET"})
     *
     * @param Branch $branch
     * @param Request $request
     * 
     * @return Response
     */
    public function edit(Branch $branch, Request $request): Response
    {
        $form = $this->createForm(BranchType::class, $branch);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->save($branch)) {
                $this->addFlash(
                    MessageConstant::SUCCESS_TYPE,
                    "La branche {$branch->getName()} a bien ete modifiee"
                );
            } else {
                $this->addFlash(
                    MessageConstant::ERROR_TYPE,
                    "Il y a une erreur pendant la modificaiton de la branche !"
                );
            }
            return $this->redirectToRoute('admin_branch_index');
        }
        return $this->render('admin/branch/edit.html.twig', [
            'branch' => $branch,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer une branche.
     * 
     * @Route("/{id}/remove", name="admin_branch_delete")
     *
     * @param Branch $branch
     * 
     * @return Response
     */
    public function delete(Branch $branch): Response
    {
        if ($this->remove($branch)) {
            $this->addFlash(
                MessageConstant::SUCCESS_TYPE,
                "La branche {$branch->getName()} a bien ete supprimee"
            );
        }
        return $this->redirectToRoute('admin_branch_index');
    }
}
