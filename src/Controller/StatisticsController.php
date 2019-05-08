<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class StatisticsController extends AbstractController
{
    /**
     * @Route("/statistics", name="statistics_index")
     */
    public function index()
    {
        return $this->render('statistics/stats_nbUtil.html.twig', [
            'controller_name' => 'StatisticsController',
        ]);
    }
}
