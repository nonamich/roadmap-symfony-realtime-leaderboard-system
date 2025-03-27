<?php

namespace App\DataFixtures;

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
        $movies = $manager->getRepository(Movie::class)->findBy(
            criteria: [],
            offset: 1,
        );

        foreach ($movies as $movie) {
            $show = new MovieShow();
            $startTime = $faker->dateTimeBetween('+1 week', '+3 week');
            $endTime = clone $startTime;

            $endTime->modify("+{$movie->getDurationInMins()} minute");

            $show->setPrice($faker->numberBetween(400, 800));
            $show->setStartTime($startTime);
            $show->setEndTime($startTime);
        }


        $manager->persist($show);
        $manager->flush();
    }
}
