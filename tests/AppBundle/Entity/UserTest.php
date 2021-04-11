<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;

class UserTest extends KernelTestCase
{
    public function getEntity()
    {
        $code = (new User());
        $code->setUsername("username");
        $code->setPassword("password");
        $code->setEmail("email@domain.fr");

        return $code;
    }

    public function assertHasErrors(User $code, int $number = 0)
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

    public function testNotBlankUsername()
    {
        $code = $this->getEntity();
        $code->setUsername("");
        $this->assertHasErrors($code,1);
    }

    public function testNotBlankEmail()
    {
        $code = $this->getEntity();
        $code->setEmail("");
        $this->assertHasErrors($code,1);
    }

    public function testInvalidEmail()
    {
        $code = $this->getEntity();
        $code->setEmail("email");
        $this->assertHasErrors($code,1);
    }

}