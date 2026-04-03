<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

// tests/Functional/HomeBookTest.php
class HomeBookTest extends WebTestCase
{
    public function testHomePageLoads(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1');
    }

    public function testBookPageLoads(): void
    {
        $client = static::createClient();
        $client->request('GET', '/livres');
        $this->assertResponseIsSuccessful();
    }
}
