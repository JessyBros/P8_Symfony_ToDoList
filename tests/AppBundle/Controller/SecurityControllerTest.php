<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    public function testSuccessfulLogin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'jessy',
            '_password' => 'test'
        ]);

        $client->submit($form);
        $client->followRedirect();
    }
}
