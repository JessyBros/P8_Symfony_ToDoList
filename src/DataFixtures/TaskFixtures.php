<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TaskFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create("fr_FR");

        for ($i = 0; $i < 5; $i++) {
            $task = new Task();
            $task->setTitle($faker->sentence(3));
            $task->setContent($faker->sentence(25));
            $task->setCreatedAt(new \DateTime(sprintf('-%d days', rand(1, 100))));
            $task->toggle(rand(0, 1));
            $manager->persist($task);

            $manager->flush();
        }
    }
}
