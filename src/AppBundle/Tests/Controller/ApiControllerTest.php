<?php 
    // src/AppBundle/Tests/Controller/ApiControllerTest.php

    namespace Ens\JobeetBundle\Tests\Controller;

    use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
     
    class ApiControllerTest extends WebTestCase
    {
      public function testListeGames()
      {
        $client = static::createClient();
     
        $crawler = $client->request('GET', '/games');
        $this->assertEquals('AppBundle\Controller\ApiController::listGamesAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','text/html; charset=UTF-8'));
      }
      public function testListeGamesApi()
      {
        $client = static::createClient();
     	
        $crawler = $client->request('GET', '/api/v1/games');
        $this->assertEquals('AppBundle\Controller\ApiController::listGamesAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
		    $this->assertTrue($client->getResponse()->headers->contains('Content-Type','text/xml; charset=UTF-8'));
      }

     public function testCreateGame()
      {
        $client = static::createClient();
      
        $crawler = $client->request('POST', '/games', array('name' => 'Fabien', 'password' => ''));
        $this->assertEquals('AppBundle\Controller\ApiController::createGameAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','text/html; charset=UTF-8'));

      }   
      public function testCreateGameApi()
      {
        $client = static::createClient();
      
        $crawler = $client->request('POST', '/api/v1/games', array('name' => 'Fabien', 'password' => ''));
        $this->assertEquals('AppBundle\Controller\ApiController::createGameAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','text/xml; charset=UTF-8'));

      }


      public function testJoinGame()
      {
        $client = static::createClient();
      
        $crawler = $client->request('POST', '/games/180/players', array('password' => '', 'game_id' => 180, 'id' => 180));
        $this->assertEquals('AppBundle\Controller\ApiController::joinGameAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','text/html; charset=UTF-8'));
      }   

      public function testPlayer2JoinedAction()
      {
        $client = static::createClient();
     
        $crawler = $client->request('GET', '/games/127/players/2');
        $this->assertEquals('AppBundle\Controller\ApiController::player2JoinedAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','text/html; charset=UTF-8'));

      }
      public function testPlayer2JoinedActionApi()
      {
        $client = static::createClient();
     
        $crawler = $client->request('GET', 'api/v1/games/127/players/2');
        $this->assertEquals('AppBundle\Controller\ApiController::player2JoinedAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','text/xml; charset=UTF-8'));

      }      
      public function testPlaceShipsAction()
      {
        $client = static::createClient();
     
        $crawler = $client->request('POST', '/games/127/ships');
        $this->assertEquals('AppBundle\Controller\ApiController::placeShipsAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertTrue(400 === $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','text/html; charset=UTF-8'));

      } 
      public function testShipsPlacedAction()
      {
        $client = static::createClient();
     
        $crawler = $client->request('GET', '/games/127/players/2/ships');
        $this->assertEquals('AppBundle\Controller\ApiController::shipsPlacedAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertTrue(404 === $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','text/html; charset=UTF-8'));
      }
      public function testShipsPlacedActionApi()
      {
        $client = static::createClient();
     
        $crawler = $client->request('GET', 'api/v1/games/127/players/2/ships');
        $this->assertEquals('AppBundle\Controller\ApiController::shipsPlacedAction', $client->getRequest()->attributes->get('_controller'));
        $this->assertTrue(404 === $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type','text/xml; charset=UTF-8'));

      }
   

    }