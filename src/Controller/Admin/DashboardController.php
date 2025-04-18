<?php

namespace App\Controller\Admin;

use App\Entity\Hall;
use App\Entity\Reservation;
use App\Entity\ReservedSeat;
use App\Entity\Seat;
use App\Entity\Movie;
use App\Entity\Showtime;
use App\Entity\ShowtimeSeat;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Dashboard')
            ->setDefaultColorScheme('dark')
            ->renderContentMaximized()
            ->renderSidebarMinimized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('CRUD');

        yield MenuItem::linkToCrud('Users', 'fas fa-users', User::class);

        yield MenuItem::linkToCrud('Halls', 'fas fa-house', Hall::class);
        yield MenuItem::linkToCrud('Seats', 'fas fa-chair', Seat::class);

        yield MenuItem::linkToCrud('Movies', 'fas fa-film', Movie::class);

        yield MenuItem::linkToCrud('Showtimes', 'fas fa-eye', Showtime::class);
        yield MenuItem::linkToCrud('Showtime Seats', 'fas fa-chair', ShowtimeSeat::class);

        yield MenuItem::linkToCrud('Reservations', 'fas fa-ticket', Reservation::class);
        yield MenuItem::linkToCrud('Reserved Seats', 'fas fa-chair', ReservedSeat::class);
    }
}
