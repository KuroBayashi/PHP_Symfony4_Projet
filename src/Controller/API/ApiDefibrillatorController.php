<?php

namespace App\Controller\API;

use App\Entity\Defibrillator;
use App\Repository\DefibrillatorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiDefibrillatorController extends AbstractController
{
    /**
     * @Route( "/api/defibrillator/all", name="api_defibrillator_getall", methods={"GET", "HEAD"} )
     */
    public function getAll(SerializerInterface $serializer, DefibrillatorRepository $defibrillatorRepository) : JsonResponse
    {
        return JsonResponse::fromJsonString($serializer->serialize(
            $defibrillatorRepository->findAll(),
            'json',
            ['groups' => 'info']
        ));
    }

    /**
     * @Route( "/api/defibrillator/visible/{minlongitude}/{maxlongitude}/{minlatitude}/{maxlatitude}",
     *     name="api_defibrillator_getvisible",
     *     methods={"GET", "HEAD"},
     *     requirements={
     *         "minlongitude"="-?\d+(\.\d+)?",
     *         "maxlongitude"="-?\d+(\.\d+)?",
     *         "minlatitude"="-?\d+(\.\d+)?",
     *         "maxlatitude"="-?\d+(\.\d+)?",
     *     }
     * )
     */
    public function getVisible(SerializerInterface $serializer, DefibrillatorRepository $defibrillatorRepository, $minlongitude, $maxlongitude, $minlatitude, $maxlatitude) : JsonResponse
    {
        return JsonResponse::fromJsonString($serializer->serialize(
            $defibrillatorRepository->findVisible($minlongitude, $maxlongitude, $minlatitude, $maxlatitude),
            'json',
            ['groups' => 'info']
        ));
    }

    /**
     * @Route( "/api/defibrillator/available", name="api_defibrillator_getavailable", methods={"GET", "HEAD"} )
     */
    public function getAvailable(SerializerInterface $serializer, DefibrillatorRepository $defibrillatorRepository) : JsonResponse
    {
        return JsonResponse::fromJsonString($serializer->serialize(
            $defibrillatorRepository->findBy(['available' => true]),
            'json',
            ['groups' => 'info']
        ));
    }


    /**
     * @Route( "/api/defibrillator/{id}", name="api_defibrillator_getone", methods={"GET", "HEAD"}, requirements={"id"="\d+"})
     */
    public function getOneById(SerializerInterface $serializer, Defibrillator $defibrillator) : JsonResponse
    {
        return JsonResponse::fromJsonString($serializer->serialize(
            $defibrillator,
            'json',
            ['groups' => 'info']
        ));
    }
}
