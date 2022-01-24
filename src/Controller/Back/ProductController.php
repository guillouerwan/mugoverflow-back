<?php

namespace App\Controller\Api;

use App\Repository\ProductRepository;
use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
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

        return $this->json(
            // Les données à sérialiser (à convertir en JSON)
            $productslist,
            // Le status code
            200,
            // Les en-têtes de réponse à ajouter (aucune)
            [],
            // Les groupes à utiliser par le Serializer
            ['groups' => 'get_products']
        ); 
    }        

    /**
     * Get one item
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


}
