<?php

namespace App\Controller\Back;

use App\Constant\MessageConstant;
use App\Controller\BaseController;
use App\Entity\Matter;
use App\Form\MatterType;
use App\Repository\MatterRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminMatterController.
 * 
 * @Route("/admin/matters")
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@email.com>
 */
class AdminMatterController extends BaseController
{
    /** @var MatterRepository */
    private MatterRepository $matterRepository;

    /**
     * AdminMatterController constructor.
     *
     * @param EntityManagerInterface $em
     * @param MatterRepository $matterRepository
     */
    public function __construct(EntityManagerInterface $em, MatterRepository $matterRepository)
    {
        parent::__construct($em);
        $this->matterRepository = $matterRepository;
    }

    /**
     * Permet de lister toutes les matieres.
     * 
     * @Route("/{page<\d+>?1}", name="admin_matter_index", methods={"POST","GET"})
     *
     * @param integer $page
     * @param PaginationService $pagination
     * 
     * @return Response
     */
    public function index(int $page, PaginationService $pagination): Response
    {
        $pagination->setEntityClass(Matter::class)
            ->setLimit(6)
            ->setPage($page);
        return $this->render('admin/matter/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Permet d'inserer une nouvelle matiere.
     * 
     * @Route("/new", name="admin_matter_new", methods={"POST","GET"})
     *
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $matter = new Matter();
        $form = $this->createForm(MatterType::class, $matter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->save($matter)) {
                $this->addFlash(
                    MessageConstant::SUCCESS_TYPE,
                    "La matiere {$matter->getName()} a bien ete creee"
                );
            } else {
                $this->addFlash(
                    MessageConstant::ERROR_TYPE,
                    "Il y a une erreur lors de l'insertion de la matiere"
                );
            }
            return $this->redirectToRoute('admin_matter_index');
        }
        return $this->render('admin/matter/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'inserer une nouvelle matiere.
     * 
     * @Route("/{id}/edit", name="admin_matter_edit", methods={"POST","GET"})
     *
     * @param Request $request
     * @param Matter $matter
     * 
     * @return Response
     */
    public function edit(Matter $matter, Request $request): Response
    {
        $form = $this->createForm(MatterType::class, $matter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->save($matter)) {
                $this->addFlash(
                    MessageConstant::SUCCESS_TYPE,
                    "La matiere {$matter->getName()} a bien ete modifiee"
                );
            } else {
                $this->addFlash(
                    MessageConstant::ERROR_TYPE,
                    "Il y a une erreur lors de la modification de la matiere"
                );
            }
            return $this->redirectToRoute('admin_matter_index');
        }
        return $this->render('admin/matter/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer une matiere.
     * 
     * @Route("/{id}/delete", name="admin_matter_delete")
     *
     * @param Matter $matter
     * @return Response
     */
    public function delete(Matter $matter): Response
    {
        if ($this->remove($matter)) {
            $this->addFlash(
                MessageConstant::SUCCESS_TYPE,
                "La matiere {$matter->getName()} a bien ete supprimee"
            );
        }
        return $this->redirectToRoute('admin_matter_index');
    }
}
