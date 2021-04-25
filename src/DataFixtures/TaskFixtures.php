<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create("fr_FR");

        for ($i = 0; $i < 4; $i++) {
            $task = new Task();
            $task->setTitle($faker->sentence(3));
            $task->setContent($faker->sentence(25));
            $task->setCreatedAt(new \DateTime(sprintf('-%d days', rand(1, 100))));
            $task->toggle(rand(0, 1));
            $manager->persist($task);
        }

        $adminTask = new Task();
        $adminTask->setTitle("Task linked to administrator");
        $adminTask->setContent("It's a demonstration, to check if this task is linked to administrator");
        $adminTask->setCreatedAt(new \DateTime(sprintf('-%d days', rand(1, 100))));
        $adminTask->toggle(0);
        $adminTask->setUser($this->getReference('admin'));
        $manager->persist($adminTask);

        $userTask = new Task();
        $userTask->setTitle("Task linked to user");
        $userTask->setContent("It's a demonstration, to check if this task is linked to user");
        $userTask->setCreatedAt(new \DateTime(sprintf('-%d days', rand(1, 100))));
        $userTask->toggle(0);
        $userTask->setUser($this->getReference('user'));
        $manager->persist($userTask);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
