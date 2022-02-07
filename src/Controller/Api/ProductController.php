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
        $path = $request->getUriForPath($this->getParameter('images_products_directory_for_uri'));

        $randomsProducts = $productRepository->findTenRandomProducts();

        $latestProducts = $productRepository->latestProducts();

        $favoriteProducts = $productRepository->findThreeRandomProducts();


        if ($request->get('type')) {
            $type = $request->get('type');
            switch ($type) {
                case 'random':
                    foreach ($randomsProducts as $product) {
                        if ($product->getMockupFront() !== null) {
                            $product->setMockupFront($path . $product->getMockupFront());
                        }
                        if ($product->getmockupOverview() !== null) {
                            $product->setmockupOverview($path . $product->getmockupOverview());
                        }
                    }
                    return $this->json(
                        $randomsProducts,
                        Response::HTTP_OK,
                        [],
                        ['groups' => 'get_products']
                    );
                    break;
                case 'favoritePromo':
                    foreach ($randomsProducts as $product) {
                        if ($product->getMockupFront() !== null) {
                            $product->setMockupFront($path . $product->getMockupFront());
                        }
                        if ($product->getmockupOverview() !== null) {
                            $product->setmockupOverview($path . $product->getmockupOverview());
                        }
                    }
                    return $this->json(
                        $randomsProducts,
                        Response::HTTP_OK,
                        [],
                        ['groups' => 'get_products']
                    );
                    break;
                case 'favoriteProduct':
                    foreach ($favoriteProducts as $product) {
                        if ($product->getMockupFront() !== null) {
                            $product->setMockupFront($path . $product->getMockupFront());
                        }
                        if ($product->getmockupOverview() !== null) {
                            $product->setmockupOverview($path . $product->getmockupOverview());
                        }
                    }
                    return $this->json(
                        $favoriteProducts,
                        Response::HTTP_OK,
                        [],
                        ['groups' => 'get_products']
                    );
                    break;
                case 'latest':
                    foreach ($latestProducts as $product) {
                        if ($product->getMockupFront() !== null) {
                            $product->setMockupFront($path . $product->getMockupFront());
                        }
                        if ($product->getmockupOverview() !== null) {
                            $product->setmockupOverview($path . $product->getmockupOverview());
                        }
                    }
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

        
            foreach ($productsList as $product) {
                if ($product->getMockupFront() !== null) {
                    $product->setMockupFront($path . $product->getMockupFront());
                }
                if ($product->getmockupOverview() !== null) {
                    $product->setmockupOverview($path . $product->getmockupOverview());
                }
            }

            return $this->json(
                $productsList,
                200,
                [],
                ['groups' => 'get_products']
            ); 
        }

        $productsList = $productRepository->findAll();

        foreach ($productsList as $product) {
            if ($product->getMockupFront() !== null) {
                $product->setMockupFront($path . $product->getMockupFront());
            }
            if ($product->getmockupOverview() !== null) {
                $product->setmockupOverview($path . $product->getmockupOverview());
            }
        }

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
    public function getProduct(Product $product = null, Request $request)
    {
        $path = $request->getUriForPath($this->getParameter('images_products_directory_for_uri'));
        if ($product === null) {
            return $this->json(['error' => 'Produit non trouvé.'], Response::HTTP_NOT_FOUND);
        }
        // For send the uri image :
        if($product->getMockupFront() !== null){
            $product->setMockupFront($path . $product->getMockupFront());
        }
        if($product->getMockupOverview() !== null){
            $product->setMockupOverview($path . $product->getMockupOverview());
        }
        if($product->getAssetBack() !== null){
            $product->setAssetBack($path . $product->getAssetBack());
        }
        if($product->getAssetFront() !== null){
            $product->setAssetFront($path . $product->getAssetFront());
        }
        return $this->json($product, Response::HTTP_OK, [], ['groups' => 'get_product']);
    }

    
}