<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{

    public function getClient()
    {
        return $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW'   => 'password',
        ));
    }

    public function testViewListUser()
    {
        $client = $this->getClient();

        $crawler = $client->request('GET', '/users');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testCreateUser()
    {
        $client = $this->getClient();
        
        $crawler = $client->request('GET', '/users/create');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'user',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'email@domain.fr'
        ]);

        $client->submit($form);
        $client->followRedirect();
        
    }

    public function testEditUser()
    {
        $client = $this->getClient();
        
        $crawler = $client->request('GET', '/users/1/edit');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Modifier')->form([
            'user[username]' => 'user',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'email@domain.fr'
        ]);

        $client->submit($form);
    }
}