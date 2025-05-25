<?php

namespace App\Controller;

use App\Entity\Species;
use App\Entity\Marker;
use App\Entity\User;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

final class MarkerController extends AbstractController
{
    #[Route('/api/markers', name: 'create_marker', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function createMarker(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): JsonResponse
    {
        
        $marker = new Marker();
        $marker->setLat($request->request->get('lat'));
        $marker->setLng($request->request->get('lng'));
        $marker->setTitle($request->request->get('title'));
        $marker->setDescription($request->request->get('description'));
        $marker->setUser($this->getUser());
        $marker->setCategory($request->request->get('category'));
        $marker->setCreatedAt(new DateTimeImmutable());

        
        $imageFile = $request->files->get('image');
        if($imageFile){
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
            //$newFilename = $safeFilename . '-' . uniqid() . '.' . "jpg";
            try{
                $imageFile->move($this->getParameter('uploads_directory'), $newFilename);
                $marker->setImage($newFilename);
            }catch(FileException $e){
                return $this->json(['error'=>"No s'ha pogut guardar l'image " . $newFilename. " ". $e->getMessage()],400);
            }
        }

        $speciesId = $request->request->get('species');
        if ($speciesId) {
            $species = $entityManager->getRepository(Species::class)->find($speciesId);
            if ($species) {
                $marker->setSpecies($species);
            } else {
                return $this->json(['error' => 'Espècie no trobada'], 400);
            }
        }

        $entityManager->persist($marker);
        $entityManager->flush();

        return $this->json([
            'message' => 'Marcador creat correctament',
            'id' => $marker->getId(),
            'lat' => $marker->getLat(),
            'lng' => $marker->getLng(),
            'title' => $marker->getTitle(),
            'description' => $marker->getDescription(),
            'image' => $marker->getImage(),
            'user' => $marker->getUser()->getNickname(),
            'userId' => $marker->getUser()->getId(),
            'species' => $marker->getSpecies() ? $marker->getSpecies()->getCommonName(): null
        ]);
    }

    #[Route('/api/markers', name: 'list_markers', methods: ['GET'])]
    public function listMarkers(EntityManagerInterface $entityManager): JsonResponse
    {
        $markers = $entityManager->getRepository(Marker::class)->findAll();

        $data = [];
        foreach($markers as $marker){
            $data[] = [
                'id' => $marker->getId(),
                'lat' => $marker->getLat(),
                'lng' => $marker->getLng(),
                'title' => $marker->getTitle(),
                'description' => $marker->getDescription(),
                'image' => $marker->getImage(),
                'user' => $marker->getUser()->getNickname(),
                'userId' => $marker->getUser()->getId(),
                'species' => $marker->getSpecies() ? $marker->getSpecies()->getCommonName(): null
            ];
        }

        return $this->json($data);
    }

    #[Route('/api/markers/{id}', name: 'get_marker', methods: ['GET'])]
    public function getMarker(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $marker = $entityManager->getRepository(Marker::class)->find($id);

        if(!$marker){
            return $this->json(['error' => 'Marcador no trovat'], 404);
        }

        return $this->json([
            'id' => $marker->getId(),
            'lat' => $marker->getLat(),
            'lng' => $marker->getLng(),
            'title' => $marker->getTitle(),
            'description' => $marker->getDescription(),
            'image' => $marker->getImage(),
            'user' => $marker->getUser()->getNickname(),
            'userId' => $marker->getUser()->getId(),
            'species' => $marker->getSpecies() ? $marker->getSpecies()->getCommonName(): null
        ]);
    }

    #[Route('/api/markers/{id}', name: 'update_marker', methods: ['PUT'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function updateMarker(int $id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $marker = $entityManager->getRepository(Marker::class)->find($id);
        if (!$marker){
            return $this->json(['error' => 'Marcador no trovat'], 404);
        }

        if (isset($data['title'])){
            $marker->setTitle($data['title']);
        }
        if (isset($data['description'])){
            $marker->setDescription($data['description']);
        }
        if (isset($data['lat'])){
            $marker->setLat($data['lat']);
        }
        if (isset($data['lng'])){
            $marker->setLng($data['lng']);
        }

        $entityManager->flush();

        return $this->json([
            'message' => 'Marcador actualitzat correctament',
            'id' => $marker->getId()
        ]);
    }

    #[Route('/api/markers/{id}', name: 'delete_marker', methods: ['DELETE'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function deleteMarker(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $marker = $entityManager->getRepository(Marker::class)->find($id);

        if(!$marker){
            return $this->json(['error'=>'Marcador no trovat'], 404);
        }
        /** @var User $user */
        $user = $this->getUser();
        if($marker->getUser()->getId() !== $user->getId() && !in_array('ROLE_ADMIN', $user->getRoles())){
            return $this->json(['error'=>'No tens permisos per eliminar aquest marcador']);
        }

        $entityManager->remove($marker);
        $entityManager->flush();

        return $this->json([
            'message' => 'Marcador eliminat correctament'
        ]);
    }
}
