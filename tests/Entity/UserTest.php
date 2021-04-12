<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;

class UserTest extends KernelTestCase
{
    
    public function getEntity()
    {
        $user = new User();
        $user->setUsername("username");
        $user->setPassword("password");
        $user->setEmail("email@domain.fr");

        return $user;
    }

    public function getTask()
    {
        $task = new Task();
        return $task;
    }

    public function assertHasErrors(User $user, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get("validator")->validate($user);
        
        $messages = [];
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . '=>' . $error->getMessage();
        }
        
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidEntity()
    {
        $this->assertHasErrors($this->getEntity(),0);
    }

    public function testNotBlankUsername()
    {
        $user = $this->getEntity();
        $user->setUsername("");
        $this->assertHasErrors($user,1);
    }

    public function testNotBlankEmail()
    {
        $user = $this->getEntity();
        $user->setEmail("");
        $this->assertHasErrors($user,1);
    }

    public function testInvalidEmail()
    {
        $user = $this->getEntity();
        $user->setEmail("email");
        $this->assertHasErrors($user,1);
    }

    public function testTasks()
    {
        $user = $this->getEntity();
        $task = $this->getTask();

        $user->addTask($task);
        $this->assertCount(1, $user->getTasks());

        $user->removeTask($task);
        $this->assertCount(0, $user->getTasks());
    }

}