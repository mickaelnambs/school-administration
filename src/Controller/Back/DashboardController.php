<?php

namespace App\Controller\Back;

use App\Service\StatsService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class HomeController.
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class DashboardController extends AbstractController
{
    /**
     * Permet d'afficher le dashboard.
     * 
     * @IsGranted("ROLE_ADMIN")
     * 
     * @Route("/", name="app_dashboard", methods={"POST","GET"})
     *
     * @param StatsService $stats
     * @return Response
     */
    public function index(StatsService $stats): Response
    {
        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $stats->getStats()
        ]);
    }
}
