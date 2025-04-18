<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use App\Repository\ShowtimeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(MovieRepository $movieRepository): Response
    {
        $movies = $movieRepository->findHomepage();

        return $this->render('pages/home.html.twig', [
            'movies' => $movies,
        ]);
    }
}
