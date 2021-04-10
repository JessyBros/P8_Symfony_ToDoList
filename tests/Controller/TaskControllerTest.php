<?php

namespace App\Tests\Controller;

use App\DataFixtures\TaskFixtures;
use App\DataFixtures\UserFixtures;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
{

    use FixturesTrait;

    public function getClientLoginAsUser()
    {
        $client = static::createClient();

        $this->loadFixtures([UserFixtures::class, TaskFixtures::class]);
        $testUser = static::$container->get(UserRepository::class)->findOneByUsername('user1');
        
        return $client->loginUser($testUser);
    }


    public function testViewListTask()
    {
        $client = $this->getClientLoginAsUser();

        $crawler = $client->request('GET', '/tasks');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testCreateTask()
    {
        $client = $this->getClientLoginAsUser();
        
        $crawler = $client->request('GET', '/tasks/create');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'newTitre',
            'task[content]' => 'newContenu'
        ]);

        $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertSelectorTextContains('div.alert-success', "Superbe ! La tâche a été bien été ajoutée.");

        $taskCreated = static::$container->get(TaskRepository::class)->findOneByTitle('newTitre');
        $this->assertSame('newTitre',$taskCreated->getTitle());
        
        $testUser = static::$container->get(UserRepository::class)->findOneByUsername('user1');
        $this->assertSame($testUser->getId(), $taskCreated->getUser()->getId());

    }
    
    public function testEditTask()
    {
        $client = $this->getClientLoginAsUser();
        
        $crawler = $client->request('GET', '/tasks/1/edit');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => 'updateTitre',
            'task[content]' => 'updateContenu'
        ]);

        $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertSelectorTextContains('div.alert-success', "Superbe ! La tâche a bien été modifiée.");
    }

    public function testToggleTask()
    {
        $client = $this->getClientLoginAsUser();
        
        $crawler = $client->request('GET', '/tasks/1/toggle');
        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());
        
    }

    public function testDeleteTask()
    {
        $client = $this->getClientLoginAsUser();
        
        $crawler = $client->request('GET', '/tasks/1/delete');
        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('div.alert-success', "Superbe ! La tâche a bien été supprimée.");
    }
    
}