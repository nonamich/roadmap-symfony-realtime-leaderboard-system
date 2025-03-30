<?php

namespace App\Controller;

use App\Entity\MovieShow;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/show')]
final class MovieShowController extends AbstractController
{
    #[Route('/{id}', name: 'app_movie_show')]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        $movieShow = $entityManager->getRepository(MovieShow::class)->findOneRelations($id);

        return $this->render('pages/open-movie-show.html.twig', [
            'show' => $movieShow,
        ]);
    }
}
