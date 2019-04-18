<?php

namespace App\Controller;

use App\Entity\Defibrillator;
use App\Form\DefibrillatorType;
use App\Repository\DefibrillatorRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/defibrillator")
 */
class DefibrillatorController extends AbstractController
{
    /**
     * @Route("/", name="defibrillator_index", methods={"GET"})
     */
    public function index(DefibrillatorRepository $repository): Response
    {
        return $this->render('defibrillator/index.html.twig', [
            'defibrillators' => $repository->findBy(['available' => true]),
        ]);
    }

    /**
     * @Route("/new", name="defibrillator_new", methods={"GET", "POST", "PUT"})
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, SerializerInterface $serializer): Response
    {
        $defibrillator = new Defibrillator();
        $form = $this->createForm(DefibrillatorType::class, $defibrillator, [
            'action' => $this->generateUrl('defibrillator_new')
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($defibrillator);
                $entityManager->flush();

                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse([
                        'defibrillator' => json_decode($serializer->serialize($defibrillator, 'json', ['groups' => 'info'])),
                        'success' => true
                    ]);
                }

                return $this->redirectToRoute('defibrillator_index');
            }
            else {
                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse([
                        'form' => $this->renderView('defibrillator/_modal_form.html.twig', [
                            'form' => $form->createView()
                        ])
                    ]);
                }
            }
        }

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'form' => $this->renderView('defibrillator/modal_edit.html.twig', [
                    'modal_title' => "Add a new defibrillator",
                    'defibrillator' => $defibrillator, 'json',
                    'form' => $form->createView()
                ])
            ]);
        }

        return $this->render('defibrillator/new.html.twig', [
            'defibrillator' => $defibrillator,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="defibrillator_show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Defibrillator $defibrillator): Response
    {
        return $this->render('defibrillator/show.html.twig', [
            'defibrillator' => $defibrillator,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="defibrillator_edit", methods={"GET","POST", "PUT"}, requirements={"id"="\d+"})
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Defibrillator $defibrillator, SerializerInterface $serializer): Response
    {
        $form = $this->createForm(DefibrillatorType::class, $defibrillator, [
            'action' => $this->generateUrl('defibrillator_edit', [
                'id' => $defibrillator->getId()
            ])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse([
                        'defibrillator' => json_decode($serializer->serialize($defibrillator, 'json', ['groups' => 'info'])),
                        'success' => true
                    ]);
                }

                return $this->redirectToRoute('defibrillator_index', [
                    'id' => $defibrillator->getId(),
                ]);
            }
            else {
                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse([
                        'form' => $this->renderView('defibrillator/_modal_form.html.twig', [
                            'form' => $form->createView()
                        ])
                    ]);
                }
            }
        }

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'form' => $this->renderView('defibrillator/modal_edit.html.twig', [
                    'modal_title' => "Edit defibrillator",
                    'defibrillator' => $defibrillator,
                    'form' => $form->createView()
                ])
            ]);
        }

        return $this->render('defibrillator/edit.html.twig', [
            'defibrillator' => $defibrillator,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/report", name="defibrillator_report", methods={"GET","POST", "PUT"}, requirements={"id"="\d+"})
     *
     * @IsGranted("ROLE_USER")
     */
    public function report(Request $request, Defibrillator $defibrillator, SerializerInterface $serializer): Response
    {
        $form = $this->createFormBuilder($defibrillator)
            ->setAction($this->generateUrl('defibrillator_report', ["id" => $defibrillator->getId()]))
            ->getForm();
        $form->handleRequest($request);

        if (($form->isSubmitted() && $form->isValid()) || $this->isCsrfTokenValid('report'.$defibrillator->getId(), $request->request->get('_token'))) {
            $defibrillator->setReported(true);
            $this->getDoctrine()->getManager()->flush();

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse([
                    'defibrillator' => json_decode($serializer->serialize($defibrillator, 'json', ['groups' => 'info']))
                ]);
            }

            return $this->redirectToRoute('defibrillator_index', [
                'id' => $defibrillator->getId(),
            ]);
        }

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'form' => $this->renderView('defibrillator/modal_report.html.twig', [
                    'modal_title' => "Report defibrillator",
                    'defibrillator' => $defibrillator,
                    'form' => $form->createView()
                ])
            ]);
        }

        return $this->redirectToRoute('defibrillator_index');
    }

    /**
     * @Route("/{id}", name="defibrillator_delete", methods={"DELETE"}, requirements={"id"="\d+"})
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Defibrillator $defibrillator): Response
    {
        $id = $defibrillator->getId();

        if ($this->isCsrfTokenValid('delete'.$defibrillator->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($defibrillator);
            $entityManager->flush();
        }

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'id' => $id,
                'success' => true
            ]);
        }

        return $this->redirectToRoute('defibrillator_index');
    }
}
