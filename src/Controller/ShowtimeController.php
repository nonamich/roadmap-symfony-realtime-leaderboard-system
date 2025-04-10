<?php

namespace App\Controller;

use App\Form\ReservationFormType;
use App\Repository\ShowtimeRepository;
use App\Repository\ShowtimeSeatRepository;
use App\Services\ReservationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\ShowtimeSeat;

#[Route('/showtime')]
final class ShowtimeController extends AbstractController
{

    public function __construct(
        private ShowtimeRepository $showtimeRepository,
        private ReservationService $reservationService
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

        $form = $this->createForm(ReservationFormType::class, options: [
            'showtime' => $showtime,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->denyAccessUnlessGranted('ROLE_USER');

            /**
             * @var ShowtimeSeat[]
             */
            $showtimeSeats = $form->get('showtimeSeats')->getData();

            $this->reservationService->reserve($showtime, $showtimeSeats);
        }

        return $this->render('pages/showtime.html.twig', [
            'showtime' => $showtime,
            'form' => $form,
        ]);
    }
}
