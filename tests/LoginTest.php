<?php

namespace App\Tests\Unit;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{
    public function testLoginPageLoads(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    public function testLoginWithValidCredentials(): void
    {
        $client = static::createClient();
        $client->request('POST', '/login', [
            '_username' => 'admin@biblio.com',
            '_password' => 'Admin1234',
        ]);
        $this->assertResponseStatusCodeSame(302);
    }

    public function testLoginPageHasFields(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertSelectorExists('input[name="_username"]');
        $this->assertSelectorExists('input[name="_password"]');
    }
}