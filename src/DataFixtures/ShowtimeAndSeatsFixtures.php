<?php

namespace App\DataFixtures;

use App\DataFixtures\HallAndSeatFixtures;
use App\DataFixtures\MovieFixtures;
use App\Entity\Hall;
use App\Entity\Movie;
use App\Entity\Showtime;
use App\Entity\ShowtimeSeat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ShowtimeAndSeatsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $movies = $manager->getRepository(Movie::class)->findAll();
        $halls = $manager->getRepository(Hall::class)->findAll();

        foreach ($halls as $hall) {
            $startTime = $faker->dateTimeBetween('+0 week', '+1 week');

            for ($index = 0; $index < 2; $index++) {
                $showtime = new Showtime();
                /** @var Movie $movie */
                $movie = $faker->randomElement($movies);
                $endTime = clone $startTime;
                $endTime->modify("+{$movie->getDurationInMins()} minute");

                $showtime->setStartTime($startTime);
                $showtime->setEndTime($endTime);
                $showtime->setHall($hall);
                $showtime->setMovie($movie);

                $manager->persist($showtime);

                $seats = $showtime->getHall()->getSeats();

                foreach ($seats as $seat) {
                    $showtimeSeat = new ShowtimeSeat();

                    $showtimeSeat->setPriceInCents($faker->numberBetween(200, 1200));
                    $showtimeSeat->setShowtime($showtime);
                    $showtimeSeat->setSeat($seat);

                    $manager->persist($showtimeSeat);
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            MovieFixtures::class,
            HallAndSeatFixtures::class,
        ];
    }
}
