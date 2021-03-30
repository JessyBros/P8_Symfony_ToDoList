<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
{

    public function getClient()
    {
        return $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW'   => 'password',
        ));
    }

    public function testViewListTask()
    {
        $client = $this->getClient();

        $crawler = $client->request('GET', '/tasks');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testCreateTask()
    {
        $client = $this->getClient();
        
        $crawler = $client->request('GET', '/tasks/create');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'titre',
            'task[content]' => 'contenu'
        ]);

        $client->submit($form);
        $client->followRedirect();
        
    }
    
    public function testEditTask()
    {
        $client = $this->getClient();
        
        $crawler = $client->request('GET', '/tasks/1/edit');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => 'titre',
            'task[content]' => 'contenu'
        ]);

        $client->submit($form);
        $client->followRedirect();
    }

    /*public function testToggleTask()
    {
        $client = $this->getClient();
        
        $crawler = $client->request('GET', '/tasks/1/toggle');

        $form = $crawler->selectButton('Marquer non terminée')->form([
            'task[isDone]' => false
        ]);

        $client->submit($form);
        $client->followRedirect();
    }

    public function testDeleteTask()
    {
    }*/
    
}