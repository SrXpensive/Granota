<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class RegisterController extends AbstractController
{
    #[Route('/api/register', name: 'app_register', methods: ['POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        ValidatorInterface $validator
    ) : JsonResponse {
        $data = json_decode($request->getContent(),true);

        if(!isset($data['email']) || !isset($data['password'])){
            return new JsonResponse(['error' => 'Faltan datos'], 400);
        }

        $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email'=>$data['email']]);
        if($existingUser){
            return new JsonResponse(['error'=>"El correu ja existeix"], 409);
        }

        $user = new User();
        $user->setNickname($data['nickname']);
        $user->setEmail($data['email']);
        $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_REVISOR']);

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Usuario registrado'], 201);
    }
}
