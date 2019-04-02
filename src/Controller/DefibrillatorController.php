<?php

namespace App\Controller;

use App\Entity\Defibrillator;
use App\Form\DefibrillatorType;
use App\Repository\DefibrillatorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/defibrillator")
 */
class DefibrillatorController extends AbstractController
{
    /**
     * @Route("/", name="defibrillator_index", methods={"GET"})
     */
    public function index(DefibrillatorRepository $defibrillatorRepository): Response
    {
        return $this->render('defibrillator/index.html.twig', [
            'defibrillators' => $defibrillatorRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="defibrillator_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $defibrillator = new Defibrillator();
        $form = $this->createForm(DefibrillatorType::class, $defibrillator);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($defibrillator);
            $entityManager->flush();

            
            return $this->json([true]); //return true when success
        }

    }

    /**
     * @Route("/{id}", name="defibrillator_show", methods={"GET"})
     */
    public function show(Defibrillator $defibrillator): Response
    {
        return $this->json([
            'defibrillator' => json_encode($defibrillator),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="defibrillator_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Defibrillator $defibrillator): Response
    {
        $form = $this->createForm(DefibrillatorType::class, $defibrillator);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->json ([true]); //return true when success
        }

        
    }

    /**
     * @Route("/{id}", name="defibrillator_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Defibrillator $defibrillator): Response
    {
        if ($this->isCsrfTokenValid('delete'.$defibrillator->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($defibrillator);
            $entityManager->flush();

            return $this->json([true]); //return true when deleted
        }

        
    }
}
