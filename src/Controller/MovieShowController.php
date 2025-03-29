<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MovieShowController extends AbstractController
{
    #[Route('/movie-show/{id}', name: 'app_movie_show')]
    public function show(): Response
    {
        return $this->render('pages/open-movie-show.html.twig', [
            'controller_name' => 'MovieShowController',
        ]);
    }
}
