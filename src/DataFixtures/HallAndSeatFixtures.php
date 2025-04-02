<?php

namespace App\DataFixtures;

use App\Entity\Hall;
use App\Entity\Seat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class HallAndSeatFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($index = 0; $index < 4; $index++) {
            $hall = new Hall();

            $hall->setName($faker->unique()->name());
            $rowsCount = $faker->numberBetween(4, 10);
            $colsCount = $faker->numberBetween($rowsCount / 2, $rowsCount);

            for ($row = 1; $row <= $rowsCount; $row++) {
                for ($col = 1; $col <= $colsCount; $col++) {
                    $seat = new Seat();
                    $letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

                    $seat->setCol($col);
                    $seat->setRow($letters[$row - 1]);
                    $hall->addSeat($seat);

                    $manager->persist($seat);
                }
            }

            $manager->persist($hall);
        }

        $manager->flush();
    }
}
