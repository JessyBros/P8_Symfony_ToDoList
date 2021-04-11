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
        $code = (new Task());
        $code->setCreatedAt(new \DateTime());
        $code->setTitle("title");
        $code->setContent("content");
        $code->toggle(true);
        
        return $code;
    }

    public function assertHasErrors(Task $code, int $number = 0)
    {
        self::bootKernel();
        $this->loadFixtures([TaskFixtures::class]);
        $errors = self::$container->get("validator")->validate($code);
        
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
        $code = $this->getEntity();
        $code->setTitle("");
        $this->assertHasErrors($code,1);
    }

    public function testNotBlankContent()
    {
        $code = $this->getEntity();
        $code->setContent("");
        $this->assertHasErrors($code,1);
    }
}