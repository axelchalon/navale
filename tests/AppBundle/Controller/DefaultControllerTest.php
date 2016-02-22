<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/games');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Liste des parties', $crawler->filter('h1')->text());
    }
}
