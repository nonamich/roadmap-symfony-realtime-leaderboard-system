<?php

namespace App\DataFixtures;

use App\Entity\Hall;
use App\Entity\Movie;
use App\Entity\MovieShow;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class MovieShowFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $movies = $manager->getRepository(Movie::class)->findAll();
        $halls = $manager->getRepository(Hall::class)->findAll();

        foreach ($halls as $hall) {
            $count_shows = $faker->numberBetween(1, 4);

            for ($i = 0; $i < $count_shows; $i++) {
                $show = new MovieShow();
                /** @var Movie $movie */
                $movie = $faker->randomElement($movies);
                $startTime = $faker->dateTimeBetween('+0 week', '+3 week');

                if ($i >= 1) {
                    $startTime->modify("+$i day");
                }

                $endTime = clone $startTime;

                $endTime->modify("+{$movie->getDurationInMins()} minute");

                $show->setPrice($faker->numberBetween(400, 800));
                $show->setStartTime($startTime);
                $show->setEndTime($startTime);
                $show->setHall($hall);
                $show->setMovie($movie);

                $manager->persist($show);
            }
        }


        $manager->flush();
    }
}
