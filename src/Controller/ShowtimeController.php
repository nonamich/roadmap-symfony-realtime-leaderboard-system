<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ReservationFormType;
use App\Repository\ShowtimeRepository;
use App\FormHandlers\ReservationFormHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/showtime')]
final class ShowtimeController extends AbstractController
{

    public function __construct(
        private ShowtimeRepository $showtimeRepository,
        private ReservationFormHandler $reservationFormHandler,
    ) {
    }

    #[Route('/{id}', name: 'app_showtime')]
    public function show(
        int $id,
        Request $request,
        #[CurrentUser] ?User $user
    ): Response {
        $showtime = $this->showtimeRepository->findOneMovieAndSeats($id);

        if (!$showtime) {
            throw $this->createNotFoundException();
        }

        if ($user) {
            $form = $this->createForm(
                ReservationFormType::class,
                options: [
                    'showtime' => $showtime,
                    'user' => $user,
                ]
            );

            $form->handleRequest($request);
            $this->reservationFormHandler->handler($form, $showtime, $user);
            $this->addFlash('success', "success!");
        }

        return $this->render('pages/showtime.html.twig', [
            'showtime' => $showtime,
            'form' => $form ?? null,
        ]);
    }
}
