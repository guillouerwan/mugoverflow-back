<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ProductController extends AbstractController

{
 
    /**  Get all products
     *
     * @Route("/api/products", name="api_products", methods={"GET"})
     */
    public function getProducts(ProductRepository $productRepository, Request $request): Response
    {

        $randomsProducts = $productRepository->findTenRandomProducts();
        $latestProducts = $productRepository->latestProducts();

        if ($request->get('type')) {
            $type = $request->get('type');
            switch ($type) {
                case 'random':
                    return $this->json(
                        $randomsProducts,
                        Response::HTTP_OK,
                        [],
                        ['groups' => 'get_products']
                    );
                    break;
                case 'favoritePromo':
                    return $this->json(
                        $randomsProducts,
                        Response::HTTP_OK,
                        [],
                        ['groups' => 'get_products']
                    );
                    break;
                case 'latest':
                    return $this->json(
                        $latestProducts,
                        Response::HTTP_OK,
                        [],
                        ['groups' => 'get_products']
                    );
                    break;
                default:
                    return $this->json(
                        ['error' => 'JSON invalide'],
                        Response::HTTP_UNPROCESSABLE_ENTITY
                    );
            }
        }

        if ($request->get('search')) {
            $productsList = $productRepository->findBySearch($request->get('search'));

            return $this->json(
                $productsList,
                200,
                [],
                ['groups' => 'get_products']
            ); 
        }

        $productsList = $productRepository->findAll();

        return $this->json(
            $productsList,
            200,
            [],
            ['groups' => 'get_products']
        ); 
       
    }        


    /**
     * Get one products with id
     * 
     * @Route("/api/products/{slug}", name="api_product", methods={"GET"})
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