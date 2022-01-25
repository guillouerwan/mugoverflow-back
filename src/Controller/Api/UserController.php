<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Form\EditProfilType;
use App\Form\UpdatePasswordType;
use App\Repository\PromoRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

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
    public function updateProfil(Request $request, ManagerRegistry $doctrine, JWTTokenManagerInterface $JWTManager): Response
    {  
        $user = $this->getUser();

        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(EditProfilType::class, $user);
        $form->submit($data);
           
        $em = $doctrine->getManager();
        $em->flush();

        return $this->json(
            [$this->getUser(),
            ['token' => $JWTManager->create($user)]
            ],
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
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface, SerializerInterface $serializer, ManagerRegistry $doctrine, ValidatorInterface $validator): Response
    {
        // Retrieval of necessary data
        try {
            $user = $serializer->deserialize($request->getContent(), User::class, 'json', [
                DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS => true,
                ]);
        } catch (NotEncodableValueException $e) {
            return $this->json(
                ['error' => 'JSON invalide'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $jsonContent = json_decode($request->getContent(), true);

        // $promo = $promoRepository->find($jsonContent["promo"]);

        $errors = $validator->validate($user);

        // Check validations

        if (count($errors) > 0) {
            $errorsClean = [];
            /** @var ConstraintViolation $error */
            foreach ($errors as $error) {
                $errorsClean[$error->getPropertyPath()][] = $error->getMessage();
            };
            return $this->json($errorsClean, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        if($jsonContent['checkPassword'] !== $jsonContent['password']){
            return $this->json(["password" => ["Les mots de passe ne correspondent pas"]],  Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        // If validation OK :

        $hashedPassword = $userPasswordHasherInterface->hashPassword($user, $user->getPassword());

        $user->setPassword($hashedPassword);

        // $promo->addUser($user);

        $em = $doctrine->getManager();
        $em->persist($user);
        $em->flush();

        return $this->json(
            "Votre compte a bien été enregistré !", 
            Response::HTTP_CREATED
        );
    }

    /**
     * For update the password
     * 
     * @Route("/api/profil/password", name="api_user_password_update", methods={"PUT"})
     */
    public function passwordUpdate(Request $request, ManagerRegistry $doctrine,  UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        // Retrieval of necessary data

        $user = $this->getUser();

        $data = json_decode($request->getContent(), true);
        
        $currentPassword = $userPasswordHasherInterface->isPasswordValid($user, $data["currentPassword"]);

        $newPassword = $data["newPassword"];
        $checkPassword = $data["checkPassword"];

        $errors = [];

        // Check passwords

        if(!$currentPassword){
            $errors["currentPassword"] = ["Mot de passe actuel incorrect"];
        }

        if($newPassword !== $checkPassword){
            $errors["newPassword"] = ["Les mots de passe ne correspondent pas"];
        }

        if(count($errors) > 0){
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        // If validation OK :

        $hashedPassword = ["password" => $userPasswordHasherInterface->hashPassword($user, $data["newPassword"])];

        $form = $this->createForm(UpdatePasswordType::class, $user);
        $form->submit($hashedPassword);

        $em = $doctrine->getManager();
        $em->flush();

        return $this->json(
            "Votre mot de passe à bien été modifié",
            200,
            [],
            ['groups' => 'user']
        );
    }
}