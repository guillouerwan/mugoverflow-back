<?php

namespace App\Controller\Api;

use App\Repository\ProductRepository;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class ProductController extends AbstractController

{
    // 
    /**  Get all products
     *
     * @Route("/api/products", name="api_products", methods={"GET"})
     */
    public function getProducts(ProductRepository $productRepository): Response
    {
        $productsList = $productRepository->findAll();

      
        return $this->json(
            // Les données à sérialiser (à convertir en JSON)
            $productsList,
            // Le status code
            200,
            // Les en-têtes de réponse à ajouter (aucune)
            [],
            // Les groupes à utiliser par le Serializer
            ['groups' => 'get_products']
        ); 

       
    }        


    /**
     * Get one products with id
     * 
     * @Route("/api/products/{id<\d+>}", name="api_product", methods={"GET"})
     */
    public function getProduct(Product $product = null)
    {
        // 404 ?
        if ($product === null) {
            return $this->json(['error' => 'Produit non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($product, Response::HTTP_OK, [], ['groups' => 'get_product']);
    }

    /**
     * Get ten random products
     * 
     * @Route("/api/products/random", name="api_products_get_item_random", methods={"GET"})
     */
    public function getItemRandom(ProductRepository $productRepository): Response
    {
        // You have to look for the products 
            $randomsProducts = $productRepository->findTenRandomProducts();

        return $this->json(
            $randomsProducts,
            Response::HTTP_OK,
            [],
            ['groups' => 'get_products']
        );
    }
      
}
