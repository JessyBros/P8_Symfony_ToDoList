<?php

namespace App\Tests\Entity;

use App\DataFixtures\TaskFixtures;
use App\Entity\Task;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;

class TaskTest extends KernelTestCase
{

    use FixturesTrait;

    public function getEntity()
    {
        $task = new Task();
        $task->setTitle("title");
        $task->setContent("content");
        $task->setCreatedAt(new \DateTime());
        $task->isDone(false);
        
        return $task;
    }

    public function assertHasErrors(Task $task, int $number = 0)
    {
        self::bootKernel();
        $this->loadFixtures([TaskFixtures::class]);
        $errors = self::$container->get("validator")->validate($task);
        
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

    public function testNotBlankTitle()
    {
        $task = $this->getEntity();
        $task->setTitle("");
        $this->assertHasErrors($task,1);
    }

    public function testNotBlankContent()
    {
        $task = $this->getEntity();
        $task->setContent("");
        $this->assertHasErrors($task,1);
    }

    public function testCreatedAt()
    {
        $task = $this->getEntity();
        
        $date = new \DateTime();
        $task->setCreatedAt($date);

        $this->assertSame($date, $task->getCreatedAt());
    }
}