<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{

    /**
     * Get the profil of the user authenticated 
     * 
     * @Route("/api/profil", name="api_user_profil", methods={"GET})
     */
    public function getProfil()
    {
        return $this->json(
            // Les données à sérialiser (à convertir en JSON)
            ['test' => 'test'],
            // Le status code
            200,
            // Les en-têtes de réponse à ajouter (aucune)
            [],
            // Les groupes à utiliser par le Serializer
            ['groups' => 'get_collection']
        );
    }
}