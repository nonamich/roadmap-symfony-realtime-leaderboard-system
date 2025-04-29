<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\ShowtimeSeat;

#[Route('/movie')]
final class MovieController extends AbstractController
{

    public function __construct(
        private MovieRepository $repository,
    ) {
    }

    #[Route('/{id}', name: 'app_movie')]
    public function show(int $id): Response
    {
        $movie = $this->repository->findOneAndShowtimes($id);

        if (!$movie) {
            throw $this->createNotFoundException();
        }

        return $this->render('pages/movie.html.twig', [
            'movie' => $movie,
        ]);
    }
}
