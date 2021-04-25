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
        $testUser = static::$container->get(UserRepository::class)->findOneByUsername('user');
        
        return $client->loginUser($testUser);
    }

    public function getClientLoginAsAdmin()
    {
        $client = static::createClient();

        $this->loadFixtures([UserFixtures::class, TaskFixtures::class]);
        $testAdmin = static::$container->get(UserRepository::class)->findOneByUsername('admin');
        
        return $client->loginUser($testAdmin);
    }


    public function testViewListTask()
    {
        $client = $this->getClientLoginAsUser();

        $crawler = $client->request('GET', '/tasks');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testViewListTaskDone()
    {
        $client = $this->getClientLoginAsUser();

        $crawler = $client->request('GET', '/tasks-done');
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
        
        $testUser = static::$container->get(UserRepository::class)->findOneByUsername('user');
        $this->assertSame($testUser->getId(), $taskCreated->getUser()->getId());

    }

    public function testTaskNotFound()
    {
        $client = $this->getClientLoginAsUser();
        
        $crawler = $client->request('GET', '/tasks/57/edit');
        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testTaskNotFound()
    {
        $client = $this->getClientLoginAsUser();
        
        $crawler = $client->request('GET', '/tasks/57/edit');
        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
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

    public function testEditOwnTask()
    {
        $client = $this->getClientLoginAsUser();
        
        $crawler = $client->request('GET', '/tasks/6/edit');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => 'updateTitre',
            'task[content]' => 'updateContenu'
        ]);

        $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertSelectorTextContains('div.alert-success', "Superbe ! La tâche a bien été modifiée.");
    }

    public function testDeleteOwnTaskUser()
    {
        $client = $this->getClientLoginAsUser();
        
        $crawler = $client->request('GET', '/tasks/6/delete');
        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('div.alert-success', "Superbe ! La tâche a bien été supprimée.");
    }
    
}