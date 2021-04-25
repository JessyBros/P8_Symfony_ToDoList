<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function hashPassword(string $password): string
    {
        return $this->encoder->encodePassword(new User(), $password);
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i < 3; $i++) {
            $user = new User();
            $user->setUsername('user'.$i);
            $user->setPassword($this->hashPassword("password"));
            $user->setEmail("user".$i."@hotmail.fr");
            $user->setRoles(["ROLE_USER"]);
            $manager->persist($user);
        }

        $admin = new User();
        $admin->setUsername("admin");
        $admin->setPassword($this->hashPassword("password"));
        $admin->setEmail("admin@hotmail.fr");
        $admin->setRoles(["ROLE_ADMIN"]);
        $manager->persist($admin);

        $user = new User();
        $user->setUsername("user");
        $user->setPassword($this->hashPassword("password"));
        $user->setEmail("user@hotmail.fr");
        $user->setRoles(["ROLE_USER"]);
        $manager->persist($user);

        $manager->flush();
        $this->addReference('admin', $admin);
        $this->addReference('user', $user);
    }
}
