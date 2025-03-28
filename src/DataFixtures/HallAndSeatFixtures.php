<?php

namespace App\DataFixtures;

use App\Entity\Hall;
use App\Entity\HallSeat;
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

            for ($row = 1; $row <= 5; $row++) {
                for ($col = 1; $col <= 5; $col++) {
                    $seat = new HallSeat();

                    $seat->setCol($col);
                    $seat->setRow($row);
                    $hall->addSeat($seat);

                    $manager->persist($seat);
                }
            }

            $manager->persist($hall);
        }

        $manager->flush();
    }
}
