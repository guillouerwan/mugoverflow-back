<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class ProductController extends AbstractController

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
    
}