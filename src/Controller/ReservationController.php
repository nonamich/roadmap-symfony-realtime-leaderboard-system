<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\User;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/reservations')]
final class ReservationController extends AbstractController
{

    public function __construct(
        private ReservationRepository $reservationRepository,
    ) {
    }

    #[IsGranted('ROLE_USER')]
    #[Route(name: 'app_reservations')]
    public function list(
        #[CurrentUser] User $user
    )
    {
        $reservations = $this->reservationRepository->findBy([
            'customer' => $user,
        ]);

        return $this->render('pages/reservations.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    #[IsGranted('show', 'reservation', 404)]
    #[Route('/{id}', name: 'app_reservation')]
    public function show(Reservation $reservation)
    {
        return $this->render('pages/reservation.html.twig', [
            'reservation' => $reservation,
        ]);
    }
}
