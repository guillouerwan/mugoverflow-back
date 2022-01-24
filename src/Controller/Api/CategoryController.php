<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class CategoryController extends AbstractController

{
    // 
    /**
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
            ['groups' => 'get_categories']
        ); 
      
    }    
     
    /**
     * Get one item
     * 
     * @Route("/api/categories/{id<\d+>}", name="api_category", methods={"GET"})
     */
    public function getCategory(Category $category = null)
    {
        // 404 ?
        if ($category === null) {
            return $this->json(['error' => 'Catégorie non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($category, Response::HTTP_OK, [], ['groups' => 'get_category']);
    }   
}