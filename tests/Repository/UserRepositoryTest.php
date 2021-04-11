<?php

namespace App\Tests\Repository;

use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    use FixturesTrait;

    public function testCount() {
        $kernel = self::bootKernel();
        $this->loadFixtures([UserFixtures::class]);
        $users = self::$container->get(UserRepository::class)->count([]);
        $this->assertEquals(4, $users);
    }
}