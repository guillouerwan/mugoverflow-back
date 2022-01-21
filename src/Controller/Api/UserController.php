<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserController extends AbstractController
{

    /**
     * Get the profil of the user authenticated 
     * 
     * @Route("/api/profil", name="api_user_profil", methods={"GET"})
     */
    public function getProfil(TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager): Response
    {

        $decodedJwtToken = $jwtManager->decode($tokenStorageInterface->getToken());

        return $this->json(
            // Les données à sérialiser (à convertir en JSON)
            $decodedJwtToken,
            // Le status code
            200,
            // Les en-têtes de réponse à ajouter (aucune)
            [],
            // Les groupes à utiliser par le Serializer
            ['groups' => 'get_collection']
        );
    }
}