<?php

namespace App\Controller;

use App\Entity\Species;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class SpeciesController extends AbstractController
{
    #[Route('/api/species', name: 'api_species_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $species = new Species();
        $species->setCommonName($data['commonName'] ?? '');
        $species->setScientificName($data['scientificName'] ?? null);
        $species->setType($data['type'] ?? '');
        $species->setObservations($data['observations'] ?? null);
        $species->setImagenes($data['imagenes'] ?? []);
        

        $em->persist($species);
        $em->flush();

        return $this->json([
            'id' => $species->getId(),
            'commonName' => $species->getCommonName(),
            'scientificName' => $species->getScientificName(),
            'observations' => $species->getObservations(),
            'type' => $species->getType(),
            'imagenes' => $species->getImagenes()
        ]);
    }

    #[Route('/api/species', name: 'api_species_list', methods: ['GET'])]
    public function list(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $type = $request->query->get('type');

        if($type){
            $speciesList = $em->getRepository(Species::class)->findBy(['type'=> $type]);
        }else{
            $speciesList = $em->getRepository(Species::class)->findAll();
        }
        

        $data = [];
        foreach ($speciesList as $species) {
            $data[] =[
                'id' => $species->getId(),
                'commonName' => $species->getCommonName(),
                'scientificName' => $species->getScientificName(),
                'observations' => $species->getObservations(),
                'type' => $species->getType(),
                'imagenes' => $species->getImagenes()
            ];
        }

        return $this->json($data);
    }

    #[Route('/api/species/{id}', name: 'api_species_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $em): JsonResponse
    {
        $species = $em->getRepository(Species::class)->find($id);
        if (!$species) {
            return $this->json(['error' => 'Species not found'], 404);
        }

        return $this->json([
            'id' => $species->getId(),
            'commonName' => $species->getCommonName(),
            'scientificName' => $species->getScientificName(),
            'observations' => $species->getObservations(),
            'type' => $species->getType(),
            'imagenes' => $species->getImagenes()
        ]);
    }

    #[Route('/api/species/{id}', name: 'api_species_update', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $species = $em->getRepository(Species::class)->find($id);
        if (!$species) {
            return $this->json(['error' => 'Espècie no trobada'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['commonName'])) {
            $species->setCommonName($data['commonName']);
        }
        if (array_key_exists('scientificName', $data)) {
            $species->setScientificName($data['scientificName']);
        }
        if (isset($data['type'])) {
            $species->setType($data['type']);
        }
        if (array_key_exists('observations', $data)) {
            $species->setObservations($data['observations']);
        }
        if (isset($data['imagenes'])) {
            $species->setImagenes($data['imagenes']);
        }

        $em->flush();

        return $this->json([
            'id' => $species->getId(),
            'commonName' => $species->getCommonName(),
            'scientificName' => $species->getScientificName(),
            'observations' => $species->getObservations(),
            'type' => $species->getType(),
            'imagenes' => $species->getImagenes()
        ]);
    }

    #[Route('/api/species/{id}', name: 'api_species_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): JsonResponse
    {
        $species = $em->getRepository(Species::class)->find($id);
        if (!$species) {
            return $this->json(['error' => 'Espècie no trobada'], 404);
        }

        $em->remove($species);
        $em->flush();

        return $this->json(null, 204);
    }
}
