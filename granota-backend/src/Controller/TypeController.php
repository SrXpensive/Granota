<?php

namespace App\Controller;

use App\Repository\SpeciesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class TypeController extends AbstractController
{   
    #[Route('/api/types', name: 'api_types', methods: ['GET'])]
    public function index(SpeciesRepository $speciesRepository): JsonResponse
    {
        $types = $speciesRepository->findAllTypes();
        return $this->json($types);
    }
}
