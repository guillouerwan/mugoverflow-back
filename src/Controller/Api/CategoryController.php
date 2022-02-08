<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class CategoryController extends AbstractController

{
    /** Get all Categories 
     * 
     * @Route("/api/categories", name="api_categories", methods={"GET"})
     */
    public function getCategories(CategoryRepository $categoryRepository, Request $request): Response
    {
        $path = $request->getUriForPath($this->getParameter('images_categories_directory_for_uri'));
        
        $categoryList = $categoryRepository->findAll();
        
        foreach ($categoryList as $category) {
            // For send the uri image :
            if ($category->getImage() !== null) {
                $category->setImage($path . $category->getImage());
            }
        }

        return $this->json(
            $categoryList,
            200,
            [],
            [
            'groups' => [
                'get_categories',
                'get_products'
            ]
        ]);
      
    }    
     
    /**
     * Get all products for a category given
     * 
     * @Route("/api/categories/{slug}/products", name="api_products_get_category", methods={"GET"})
     */
    public function getProductsOfCategory(Category $category, ProductRepository $productRepository, Request $request): Response
    {
        $path = $request->getUriForPath($this->getParameter('images_categories_directory_for_uri'));

        $pathProduct = $request->getUriForPath($this->getParameter('images_products_directory_for_uri'));

        if ($category=== null) {
            return $this->json(['error' => 'catégorie non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        // For send the uri image :
        if ($category->getImage() !== null) {
            $category->setImage($path . $category->getImage());
        }
        
        $productList = $category->getProducts();

        foreach ($productList as $product) {
            
            // For send the uri image :
            if ($product->getMockupFront() !== null) {
                $product->setMockupFront($pathProduct . $product->getMockupFront());
            }

            if ($product->getmockupOverview() !== null) {
                $product->setmockupOverview($pathProduct . $product->getmockupOverview());
            }
        }
       
        // Data to return
        $data = [
            'category' => $category,
            'products' => $productList,
        ];

        return $this->json(
        $data,
        Response::HTTP_OK,
        [],
        [
            'groups' => [
                'get_categories',
                'get_products'
            ]
        ]);
    }
    
}