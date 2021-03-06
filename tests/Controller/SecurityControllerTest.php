<?php

namespace App\Tests\Controller;

use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    use FixturesTrait;

    public function testSuccessfulLogin()
    {
        $client = static::createClient();
        $this->loadFixtures([UserFixtures::class]);
        $userRepository = static::$container->get(UserRepository::class);
        
        $crawler = $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'user',
            '_password' => 'password'
        ]);

        $client->submit($form);
        $this->assertResponseRedirects("", Response::HTTP_FOUND);
        $crawler = $client->followRedirect();
        
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");
        $this->assertSelectorTextContains('strong', "user");
        $this->assertSelectorTextContains('a.btn-danger', "Se déconnecter");

    }

    public function testInvalidCredentialLogin()
    {
       $client = static::createClient();

        $crawler = $client->request('GET', '/login');        
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'fakeUser',
            '_password' => 'fakePassword'
        ]);

        $client->submit($form);
        $crawler = $client->followRedirect();
       
        $this->assertSelectorTextContains('div.alert-danger', "Invalid credentials.");
    }
}
