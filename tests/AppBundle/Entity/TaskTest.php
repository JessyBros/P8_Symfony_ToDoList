<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;

class TaskTest extends KernelTestCase
{
    public function getEntity()
    {
        $code = (new Task());
        $code->setCreatedAt(new \DateTime());
        $code->setTitle("title");
        $code->setContent("content");
        $code->isDone();
        
        return $code;
    }

    public function assertHasErrors(Task $code, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$kernel->getContainer()->get("validator")->validate($code);
        
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