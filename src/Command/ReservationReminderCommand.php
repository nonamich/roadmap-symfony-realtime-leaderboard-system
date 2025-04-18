<?php

namespace App\Command;

use App\Repository\ReservationRepository;
use App\Services\ReservationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:reservation-reminder',
)]
class ReservationReminderCommand extends Command
{
    public function __construct(
        private ReservationRepository $reservationRepository,
        private ReservationService $reservationService,
        private EntityManagerInterface $entityManagerInterface,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $duration = $io->ask('Interval duration', '1 hour');
        $interval = \DateInterval::createFromDateString($duration);
        $start = new \DateTimeImmutable();
        $end = $start->add($interval);
        $reservations = $this->reservationRepository->findOneBetween($start, $end);

        $io->info(
            sprintf('found %s reservations to remind', count($reservations))
        );

        $this->reservationService->remind($reservations);

        return Command::SUCCESS;
    }
}
