<?php

namespace App\Controller\Api;

use App\Entity\Promo;
use App\Entity\User;
use App\Form\RegisterType;
use App\Form\EditProfilType;
use App\Repository\PromoRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\JsonLoginFactory;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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

        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(EditProfilType::class, $user);
        $form->submit($data);
           
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
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface,SerializerInterface $serializer, ManagerRegistry $doctrine, ValidatorInterface $validator, PromoRepository $promoRepository): Response
    {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');

        $jsonContent = json_decode($request->getContent(), true);

        $promo = $promoRepository->find($jsonContent["promo"]);

        if(!$promo){
            return $this->json("La promo saisie n'existe pas.", 500);
        }

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            return $this->json($errors, 500);
        }

        $hashedPassword = $userPasswordHasherInterface->hashPassword($user, $user->getPassword());

        $user->setPassword($hashedPassword);

        $promo->addUser($user);

        $em = $doctrine->getManager();
        $em->persist($user);
        $em->flush();

        return $this->json(
            "Votre compte a bien été enregistré !", 
            Response::HTTP_CREATED
        );
    }
}