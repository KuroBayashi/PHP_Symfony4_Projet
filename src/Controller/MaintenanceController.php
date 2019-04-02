<?php

namespace App\Controller;

use App\Entity\Maintenance;
use App\Form\MaintenanceType;
use App\Repository\MaintenanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/maintenance")
 */
class MaintenanceController extends AbstractController
{
    /**
     * @Route("/", name="maintenance_index", methods={"GET"})
     */
    public function index(MaintenanceRepository $maintenanceRepository): Response
    {
        return $this->render('maintenance/index.html.twig', [
            'maintenances' => $maintenanceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="maintenance_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $maintenance = new Maintenance();
        $form = $this->createForm(MaintenanceType::class, $maintenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($maintenance);
            $entityManager->flush();

            return $this->redirectToRoute('maintenance_index');
        }

    }

    /**
     * @Route("/{id}", name="maintenance_show", methods={"GET"})
     */
    public function show(Maintenance $maintenance): Response
    {
        return $this->render('maintenance/show.html.twig', [
            'maintenance' => $maintenance,
        ]);
    }

}
