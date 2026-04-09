<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeBookTest extends WebTestCase
{
    public function testHomePageLoads(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
    }

    public function testBookIndexLoads(): void
    {
        $client = static::createClient();
        $client->request('GET', '/livres');
        $this->assertResponseIsSuccessful();
    }

    public function testLoginPageLoads(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();
    }

    public function testRegisterPageLoads(): void
    {
        $client = static::createClient();
        $client->request('GET', '/register');
        $this->assertResponseIsSuccessful();
    }

    public function testReservationRequiresLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/reservation/1');
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testFavorisRequiresLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/favoris/1');
        $this->assertResponseStatusCodeSame(302);
    }

        public function testUserPageRequiresLogin(): void
        {
            $client = static::createClient();
            $client->request('GET', '/user');
            $this->assertResponseRedirects('http://localhost/login');
        }
    public function testAdminRequiresLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/admin');
        $this->assertResponseRedirects('http://localhost/login');
    }
}