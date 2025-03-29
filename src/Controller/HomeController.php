<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use App\Repository\MovieShowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(MovieShowRepository $movieShowRepository): Response
    {
        $shows = $movieShowRepository->findAllWithMovie();

        return $this->render('pages/home.html.twig', [
            'shows' => $shows,
        ]);
    }
}
