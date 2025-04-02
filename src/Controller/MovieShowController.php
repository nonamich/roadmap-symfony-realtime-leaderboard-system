<?php

namespace App\Controller;

use App\Entity\Showtime;
use App\Form\ReservationFormType;
use App\Repository\SeatRepository;
use App\Repository\ShowtimeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/show')]
final class MovieShowController extends AbstractController
{
    #[Route('/{id}', name: 'app_movie_show')]
    public function show(int $id, ShowtimeRepository $repository): Response
    {
        $showtime = $repository->findOneRelations($id);
        $form = $this->createForm(ReservationFormType::class, options: [
            'showtime' => $showtime,
        ]);

        return $this->render('pages/open-movie-show.html.twig', [
            'showtime' => $showtime,
            'form' => $form,
        ]);
    }
}
