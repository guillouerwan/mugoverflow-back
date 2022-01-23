<?php

namespace App\Controller\Api;

use App\Form\EditProfilType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{

    /**
     * Get the profil of the user authenticated 
     * 
     * @Route("/api/profil", name="api_user_profil", methods={"GET"})
     */
    public function getProfil(): Response
    {
        $user = $this->getUser();

        return $this->json(
            // Data to serialized
            $user,
            // Status code
            200,
            // Headers
            [],
            // Groups used for the serializer
            ['groups' => 'user']
        );
    }

    /**
     * update our own profil
     * 
     * @Route("/api/profil/update", name="api_user_profil_update", methods={"PUT"})
     */
    public function updateProfil(Request $request, ManagerRegistry $doctrine): Response
    {  
        $user = $this->getUser();

        $date = json_decode($request->getContent(), true);

        $form = $this->createForm(EditProfilType::class, $user);
        $form->submit($date);
           
        $em = $doctrine->getManager();
        $em->flush();

        return $this->json(
            $this->getUser(),
            200,
            [],
            ['groups' => 'user']
        );
    }
}