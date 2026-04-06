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
        $this->assertSelectorExists('input[name="_username"]');
        $this->assertSelectorExists('input[name="_password"]');
    }

    public function testLoginRedirectsAfterSuccess(): void
    {
        $client = static::createClient();
        $client->request('POST', '/login', [
            '_username' => 'admin@biblio.com',
            '_password' => 'Admin1234',
        ]);
        $this->assertResponseRedirects();
    }

    public function testLoginFailsWithBadCredentials(): void
    {
        $client = static::createClient();
        $client->request('POST', '/login', [
            '_username' => 'faux@test.com',
            '_password' => 'mauvais',
        ]);
        $client->followRedirect();
        $this->assertSelectorExists('form');
    }
}