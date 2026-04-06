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
        $this->assertSelectorExists('form');
    }

    public function testBookSearchWorks(): void
    {
        $client = static::createClient();
        $client->request('GET', '/livres?q=Prince');
        $this->assertResponseIsSuccessful();
    }

    public function testBookShowLoads(): void
    {
        $client = static::createClient();
        $client->request('GET', '/livres/1');
        $this->assertResponseIsSuccessful();
    }

    public function testReservationRequiresLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/reservation/1');
        $this->assertResponseRedirects('/login');
    }

    public function testFavorisRequiresLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/favoris/1');
        $this->assertResponseRedirects('/login');
    }
}