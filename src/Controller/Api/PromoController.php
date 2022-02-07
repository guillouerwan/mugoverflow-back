<?php

namespace App\Controller\Api;

use App\Repository\PromoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class PromoController extends AbstractController
{

    /**  Get all promo
     *
     * @Route("/api/promos", name="api_promo", methods={"GET"})
     */
    public function getProducts(PromoRepository $promoRepository): Response
    {
        $promoList = $promoRepository->findBy([], ['name' => 'ASC']);

        return $this->json(
            $promoList,
            200,
            [],
            ['groups' => 'get_promos']
        ); 
    }        
}