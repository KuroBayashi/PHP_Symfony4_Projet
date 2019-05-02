<?php

namespace App\Controller\API;

use App\Entity\Utilization;
use App\Repository\UtilizationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiUtilizationController extends AbstractController
{

    /**
     * @Route( "/api/utilization/all", name="api_utilization_getall", methods={"GET", "HEAD"} )
     */
    public function getAll(SerializerInterface $serializer, UtilizationRepository $utilizationRepository) : JsonResponse
    {
        return JsonResponse::fromJsonString($serializer->serialize(
            $utilizationRepository->findAll(),
            'json',
            ['groups' => 'info']
        ));
    }

    /**
     * @Route( "/api/utilization/nb_util", name="api_utilization_nb_util", methods={"GET", "HEAD"} )
     */
    public function nbUtil(SerializerInterface $serializer, UtilizationRepository $utilizationRepository) : JsonResponse
    {
        return JsonResponse::fromJsonString($serializer->serialize(
            $utilizationRepository->findUtil(),
            'json'
        ));
    }

   
}
