<?php

namespace App\Controller;

use App\Entity\Marker;
use App\Entity\MarkerNote;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class MarkerNoteController extends AbstractController
{
    #[Route('/marker/notes', name: 'app_marker_note')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/MarkerNoteController.php',
        ]);
    }

    #[Route('/api/markers/{id}/notes', name: 'marker_add_note', methods: ['POST'])]
    #[IsGranted('ROLE_REVISOR')]
    public function addNote(Marker $marker, Request $request, EntityManagerInterface $em, Security $security): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $noteText = $data['note'] ??  null;

        if(!$noteText){
            return new JsonResponse(['error' => 'Nota buida'], 400);
        }

        $note = new MarkerNote();
        $note->setNote($noteText);
        $note->setCreatedAt(new \DateTimeImmutable());
        $note->setAuthor($security->getUser());
        $note->setMarker($marker);

        $em->persist($note);
        $em->flush();

        return new JsonResponse(['message' => 'Nota afegida correctament'], 201);
    }
}
