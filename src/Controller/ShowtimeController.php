<?php

namespace App\Controller;

use App\Form\ReservationFormType;
use App\Repository\ShowtimeRepository;
use App\Form\Handlers\ReservationFormHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/showtime')]
final class ShowtimeController extends AbstractController
{

    public function __construct(
        private ShowtimeRepository $showtimeRepository,
        private ReservationFormHandler $reservationFormHandler
    ) {
    }

    #[Route('/{id}', name: 'app_showtime')]
    public function show(
        int $id,
        Request $request,
    ): Response {
        $showtime = $this->showtimeRepository->findOneMovieAndSeats($id);

        if (!$showtime) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(
            ReservationFormType::class,
            options: [
                'showtime' => $showtime,
            ]
        );

        $form->handleRequest($request);
        $this->reservationFormHandler->handleCreate($form, $showtime);

        return $this->render('pages/showtime.html.twig', [
            'showtime' => $showtime,
            'form' => $form,
        ]);
    }
}
