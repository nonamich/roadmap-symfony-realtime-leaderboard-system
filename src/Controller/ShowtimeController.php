<?php

namespace App\Controller;

use App\Form\ReservationFormType;
use App\Repository\ShowtimeRepository;
use App\Repository\ShowtimeSeatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\ShowtimeSeat;

#[Route('/showtime')]
final class ShowtimeController extends AbstractController
{
    #[Route('/{id}', name: 'app_showtime')]
    public function show(
        int $id,
        Request $request,
        ShowtimeSeatRepository $showtimeSeatRepository,
        ShowtimeRepository $showtimeRepository
    ): Response {
        $showtime = $showtimeRepository->findOneMovieAndSeats($id);

        if (!$showtime) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(ReservationFormType::class, options: [
            'showtime' => $showtime,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            /**
             * @var ShowtimeSeat[]
             */
            $seats = $data['showtimeSeats'];

            $seats;
        }

        return $this->render('pages/open-movie-show.html.twig', [
            'showtime' => $showtime,
            'form' => $form,
        ]);
    }
}
