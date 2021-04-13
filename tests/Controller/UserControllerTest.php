<?php

namespace App\Tests\Controller;

use App\DataFixtures\UserFixtures;
use App\Entity\User;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    use FixturesTrait;

    public function getClientLoginAsUser()
    {
        $client = static::createClient();

        $this->loadFixtures([UserFixtures::class]);
        $testUser = static::$container->get(UserRepository::class)->findOneByUsername('user');
        
        return $client->loginUser($testUser);
    }

    public function getClientLoginAsAdmin()
    {
        $client = static::createClient();

        $this->loadFixtures([UserFixtures::class]);
        $testAdmin = static::$container->get(UserRepository::class)->findOneByUsername('admin');
        
        return $client->loginUser($testAdmin);
    }

    public function testSuccesfulViewListUser()
    {
        $client = $this->getClientLoginAsAdmin();
        $crawler = $client->request('GET', '/users');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h1', 'Liste des utilisateurs');
    }

    public function testFailViewListUser()
    {
        $client = $this->getClientLoginAsUser();
        $crawler = $client->request('GET', '/users');

        $this->assertEquals(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    public function testCreateUser()
    {
        $client = $this->getClientLoginAsUser();

        $crawler = $client->request('GET', '/users/create');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'newUser',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'newUser@domain.fr',
            'user[roles]' => 'ROLE_USER'
        ]);

        $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertSelectorTextContains('div.alert-success', "Superbe ! L'utilisateur a bien été ajouté.");

        $testUser = static::$container->get(UserRepository::class)->findOneByUsername('newUser');
        $this->assertInstanceOf(User::class,$testUser);
        
    }

    public function testFailCreateUserNameAlreadyExist()
    {
        $client = $this->getClientLoginAsUser();
        $crawler = $client->request('GET', '/users/create');

        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'user1',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'validEmail@domain.fr'
        ]);

        $client->submit($form);
        $this->assertSelectorTextContains('span', "This value is already used.");
        
    }

    public function testFailCreateUserEmailAlreadyExist()
    {
        $client = $this->getClientLoginAsUser();
        $crawler = $client->request('GET', '/users/create');
        $this->assertResponseIsSuccessful();
    
        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'validUser',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'user1@hotmail.fr'
        ]);

        $client->submit($form);
        $this->assertSelectorTextContains('span', "This value is already used.");
        
    }

    public function testEditUser()
    {
        $client = $this->getClientLoginAsAdmin();
        $crawler = $client->request('GET', '/users/3/edit');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Modifier')->form([
            'user[username]' => 'newUser',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'newUser@hotmail.fr'
        ]);

        $client->submit($form);
        $this->assertResponseRedirects("/users", Response::HTTP_FOUND);
        $crawler = $client->followRedirect();
        $this->assertSelectorTextContains('div.alert-success', "Superbe ! L'utilisateur a bien été modifié");
    }
}