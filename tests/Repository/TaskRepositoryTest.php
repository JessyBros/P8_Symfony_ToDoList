<?php

namespace App\Tests\Repository;

use App\DataFixtures\TaskFixtures;
use App\Repository\TaskRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskRepositoryTest extends KernelTestCase
{
    use FixturesTrait;

    public function testCount() {
        $kernel = self::bootKernel();
        $this->loadFixtures([TaskFixtures::class]);
        $users = self::$container->get(TaskRepository::class)->count([]);
        $this->assertEquals(6, $users);
    }
}