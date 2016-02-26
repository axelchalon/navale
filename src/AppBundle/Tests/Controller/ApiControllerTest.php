<?php 
    // src/AppBundle/Tests/Controller/ApiControllerTest.php

    namespace Ens\JobeetBundle\Tests\Controller;

    use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
     
    class ApiControllerTest extends WebTestCase
    {
      public function testListeGames()
      {
        $client = static::createClient();
     
        $crawler = $client->request('GET', '/');
        $this->assertEquals('AppBundle\Controller\ApiController::listGamesAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','text/html; charset=UTF-8'));
      }
      public function testListeGamesApi()
      {
        $client = static::createClient();
     	
        $crawler = $client->request('GET', '/api/v1/games', array(), array(), array('ACCEPT' => 'application/json'));
        $this->assertEquals('AppBundle\Controller\ApiController::listGamesAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        // $this->assertTrue($client->getResponse()->headers->contains('Content-Type','application/json'));
      }

     public function testCreateGame()
      {
        $client = static::createClient();
      
        $crawler = $client->request('POST', '/games', array('name' => 'Ma partie'));
        $this->assertEquals('AppBundle\Controller\ApiController::createGameAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','text/html; charset=UTF-8'));

      }   
      public function testCreateGameApi()
      {
        $client = static::createClient();
      
        $crawler = $client->request('POST', '/api/v1/games', array('name' => 'Ma deuxième partie'), array(), array('ACCEPT' => 'application/json'));
        $this->assertEquals('AppBundle\Controller\ApiController::createGameAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        // $this->assertTrue($client->getResponse()->headers->contains('Content-Type','application/json'));

      }

        public function testJoinGame()
        {
            $client = static::createClient();

            $crawler = $client->request('POST', '/games/2/players');
            $this->assertEquals('AppBundle\Controller\ApiController::joinGameAction', $client->getRequest()->attributes->get('_controller'));
            $this->assertTrue($client->getResponse()->headers->contains('Content-Type','text/html; charset=UTF-8'));
        }


        public function testPlayer2JoinedAction()
      {
        $client = static::createClient();
     
        $crawler = $client->request('GET', '/games/1/players/2');
        $this->assertEquals('AppBundle\Controller\ApiController::player2JoinedAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertTrue(404 === $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','text/html; charset=UTF-8'));

      }
      public function testPlayer2JoinedActionApi()
      {
        $client = static::createClient();
     
        $crawler = $client->request('GET', 'api/v1/games/1/players/2', array(), array(), array('ACCEPT' => 'application/json'));
        $this->assertEquals('AppBundle\Controller\ApiController::player2JoinedAction', $client->getRequest()->attributes->get('_controller'));
        
        $this->assertTrue(404 === $client->getResponse()->getStatusCode());
        // $this->assertTrue($client->getResponse()->headers->contains('Content-Type','application/json'));

      }      
      public function testPlaceShipsAction()
      {
        $client = static::createClient();
     
        $crawler = $client->request('POST', '/games/1/ships');
        $this->assertEquals('AppBundle\Controller\ApiController::placeShipsAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertTrue(400 === $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','text/html; charset=UTF-8'));

      } 
      public function testShipsPlacedAction()
      {
        $client = static::createClient();
     
        $crawler = $client->request('GET', '/games/1/players/2/ships', array(), array(), array('ACCEPT' => 'application/json'));
        $this->assertEquals('AppBundle\Controller\ApiController::shipsPlacedAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertTrue(404 === $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','text/html; charset=UTF-8'));
      }
      public function testShipsPlacedActionApi()
      {
        $client = static::createClient();
     
        $crawler = $client->request('GET', 'api/v1/games/1/players/2/ships', array(), array(), array('ACCEPT' => 'application/json'));
        $this->assertEquals('AppBundle\Controller\ApiController::shipsPlacedAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertTrue(404 === $client->getResponse()->getStatusCode());
        // $this->assertTrue($client->getResponse()->headers->contains('Content-Type','application/json'));

      }
   

    }