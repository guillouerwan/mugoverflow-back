<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class CategoryController extends AbstractController

{
    // 
    /** Get all Categories 
     * 
     * @Route("/api/categories", name="api_categories", methods={"GET"})
     */
    public function getCategories(CategoryRepository $categoryRepository): Response
    {
        $categoryList = $categoryRepository->findAll();
      
        return $this->json(
            // Les données à sérialiser (à convertir en JSON)
            $categoryList,
            // Le status code
            200,
            // Les en-têtes de réponse à ajouter (aucune)
            [],
            // Les groupes à utiliser par le Serializer
            [
            'groups' => [
                // Le groupe des catégories
                'get_categories',
                // Le groupe des products
                'get_products'
            ]
        ]);
      
    }    
     
    /**
     * Get all products of one category
     * @Route("/api/categories/{slug}/products", name="api_products_get_category", methods={"GET"})
     */
    public function getProductsOfCategory(Category $category, ProductRepository $productRepository): Response
    {
        // 404 ?
        if ($category=== null) {
            return $this->json(['error' => 'catégorie non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $productList = $category->getProducts();
       
        // Tableau PHP à convertir en JSON
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
                // Le groupe des catégories
                'get_categories',
                // Le groupe des products
                'get_products'
            ]
        ]);
}
    
}