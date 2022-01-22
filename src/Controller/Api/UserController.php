<?php

namespace App\Controller\Api;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class UserController extends AbstractController
{

    /**
     * Get the profil of the user authenticated 
     * 
     * @Route("/api/profil", name="api_user_profil", methods={"GET"})
     */
    public function getProfil(Security $security): Response
    {
        $user = $security->getUser();

        return $this->json(
            // Les données à sérialiser (à convertir en JSON)
            $user,
            // Le status code
            200,
            // Les en-têtes de réponse à ajouter (aucune)
            [],
            // Les groupes à utiliser par le Serializer
            ['groups' => 'user']
        );
    }

    /**
     * update profil our own profil
     * 
     * @Route("/api/profil/update", name="api_user_profil_update", methods={"POST"})
     */
    public function updateProfil(Request $request, SerializerInterface $serializer, Security $security): Response
    {
        $user = $security->getUser();

        // Récupérer le contenu JSON
        $jsonContent = $request->getContent();
                
        try {
            // deserialize the JSON entity to Doctrine User
            $updateUser = $serializer->deserialize($jsonContent, User::class, 'json');
        } catch (NotEncodableValueException $e) {
            // If the JSON is not conforme or missing
            return $this->json(
                ['error' => 'JSON invalide'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $this->json(
            // Les données à sérialiser (à convertir en JSON)
            $user,
            // Le status code
            200,
            // Les en-têtes de réponse à ajouter (aucune)
            [],
            // Les groupes à utiliser par le Serializer
            ['groups' => 'user']
        );
    }
}