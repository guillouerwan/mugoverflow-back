<?php

namespace App\Controller\Api;

use App\Form\EditProfilType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

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

    /**
     * register a new user
     * 
     * @Route("/api/register", name="api_user_register", methods={"POST"})
     */
    public function register(Request $request, SerializerInterface $serializer, ManagerRegistry $doctrine, ValidatorInterface $validator): Response
    {

        $jsonContent = $request->getContent();

        try {
            $user = $serializer->deserialize($jsonContent, User::class, 'json');
        } catch (NotEncodableValueException $e) {
            // Si le JSON fourni est "malformé" ou manquant, on prévient le client
            return $this->json(
                ['error' => 'JSON invalide'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $errors = $validator->validate($user);

        // Y'a-t-il des erreurs ?
        if (count($errors) > 0) {
            $errorsClean = [];

            /** @var ConstraintViolation $error */
            foreach ($errors as $error) {
                $errorsClean[$error->getPropertyPath()][] = $error->getMessage();
            };

            return $this->json($errorsClean, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // On sauvegarde l'entité
        $entityManager = $doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json(
            "Votre compte a bien été enregistré !", 
            Response::HTTP_CREATED
        );
    }
}