<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/admin/users')]
#[IsGranted('ROLE_ADMIN')]
class AdminUserController extends AbstractController
{
    private UserRepository $userRepository;
    private EntityManagerInterface $em;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    #[Route('', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $users = $this->userRepository->findAll();

        $data = array_map(fn(User $user) => [
            'id' => $user->getId(),
            'nickname' => $user->getNickname(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles()
        ], $users);

        return $this->json($data);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        $user = $this->userRepository->find($id);
        if(!$user){
            return $this->json(['error' => 'Usuari no trobat'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if(isset($data['nickname'])){
            $user->setNickname($data['nickname']);
        }

        if(isset($data['roles']) && is_array($data['roles'])){
            $allowedRoles = ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_REVISOR'];
            $filteredRoles = array_filter($data['roles'], fn($role)=>in_array($role, $allowedRoles));
            $user->setRoles($filteredRoles);
        }

        $this->em->flush();

        return $this->json([
            'id' => $user->getId(),
            'nickname' => $user->getNickname(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles()
        ]);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);
        if(!$user){
            return $this->json(['error' => 'Usuari no trobat'], 404);
        }

        $this->em->remove($user);
        $this->em->flush();

        return $this->json(['message' => 'Usuari eliminat']);
    }
}