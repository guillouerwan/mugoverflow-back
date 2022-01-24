<?php

namespace App\Controller\Api;

use App\Repository\ProductRepository;
use App\Entity\Category;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


class ProductController extends AbstractController

{
    // 
    /**
     * @Route("/api/products", name="api_products", methods={"GET"})
     */
    public function getProducts(ProductRepository $ProductRepository): Response
    {
        $productslist = $ProductRepository->findAll();

        return  $this->json($productslist);
    }


    // 
    /**
     * @Route("/api/products/{id} ", name="api_product", methods={"GET"})
     */
    public function getProduct(ProductRepository $ProductRepository): Response
    {
        $productlist = $ProductRepository->find();
        
        return  $this->json($productlist);
    }


}
