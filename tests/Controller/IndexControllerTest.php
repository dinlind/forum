<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains(
            'category0',
            $client->getResponse()->getContent()
        );
    }

    public function testAbout()
    {
        $client = static::createClient();
        $client->request('GET', '/about');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains(
            'An Internet forum',
            $client->getResponse()->getContent()
        );
    }

    public function testTerms()
    {
        $client = static::createClient();
        $client->request('GET', '/terms-and-conditions');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains(
            'At certain points in the Forum website ("Forum") navigation',
            $client->getResponse()->getContent()
        );
    }
}
