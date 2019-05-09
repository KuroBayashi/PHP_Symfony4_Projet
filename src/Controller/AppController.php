<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index()
    {
        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }

    /**
     * @Route("/home", name="app_home")
     */
    public function home()
    {
        return $this->render('app/home.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }
    /**
     * @Route("/contact", name="security_contact")
     */
        public function contact()
    {
        return $this->render('security/contact.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }
	/**
     * @Route("/stats_index", name="stats_index")
     */
    
            public function stats_index()
    {
        return $this->render('statistics/stats_nbUtil.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }
	/**
     * @Route("/stats_maintenance", name="stats_maintenance")
     */
    
            public function stats_maintenance()
    {
        return $this->render('statistics/stats_maintenances.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }
 
	      	
}


