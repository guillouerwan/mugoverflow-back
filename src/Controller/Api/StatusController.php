<?php

namespace App\Controller\Api;

use App\Repository\StatusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class StatusController extends AbstractController
{

    /**
     * Get all status
     *
     * @Route("/api/status", name="api_status", methods={"GET"})
     */
    public function getProducts(StatusRepository $statusRepository): Response
    {
        $statusList = $statusRepository->findAll();

        return $this->json(
            $statusList,
            200,
            [],
            ['groups' => 'get_status']
        ); 
    }        
}