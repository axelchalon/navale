<?php
// src/AppBundle/Tests/Repository/GameRepositoryTest.php

namespace AppBundle\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameRepositoryTest extends WebTestCase
{
    private $gameRepository;
    private $gamePublicRepo;
    private $gamesByName;

    public function setUp()
    {  
        // Accède à la méthode findOpenGames() du répository GameRepository
        $kernel = static::createKernel();
        $kernel->boot();
        $this->gameRepository = $kernel->getContainer()
                                       ->get('doctrine.orm.entity_manager')
                                       ->getRepository('AppBundle:Game')->findOpenGames();
        // méthode findOpenPublicGames()
        $this->gamePublicRepo = $kernel->getContainer()
                                        ->get('doctrine.orm.entity_manager')
                                        ->getRepository('AppBundle:Game')->findOpenPublicGames();
        // méthode findGamesByNameFuzzy()
        $this->gamesByName = $kernel->getContainer()
                                        ->get('doctrine.orm.entity_manager')
                                        ->getRepository('AppBundle:Game')->findGamesByNameFuzzy('Recusandae asperiores accusamus nihil.');
    }

    // TEST findOpenGames()

    public function openGames()
    {
        $games = $this->gameRepository;

        // Pour chaque partie
        foreach ($games as $value) 
        {   // Si le joueur 2 possède un MDP secret, on retourne FALSE
            if($value->getP2Secret() !== null)
            {
                return false;
            }
        }
        // Sinon tout est OK !
        return true;
    }
    public function testOpenGames()
    {   
        $this->assertTrue($this->openGames());
    }


    // TEST findOpenPublicGames()

    public function openPublicGames()
    {
        $games = $this->gamePublicRepo;
        foreach ($games as $value2)
        {   // Si le joueur 2 possède un secret ou que la partie a un mot de passe, on return FALSE
            if(($value2->getP2Secret() !== null) OR ($value2->getPassword() !== null)){
                return false;
            }
        }
        return true;
    }
    public function testOpenPublicGames()
    {
        $this->assertTrue($this->openPublicGames());
    }


    // TEST findGamesByNameFuzzy()

    public function searchGameByName()
    {
        $game = $this->gamesByName;
        // S'il trouve une partie avec p2secret égal à nul
        if(($game[0]->getName() === 'Recusandae asperiores accusamus nihil.') AND ($game[0]->getP2Secret() === null)){
            return true;
        }
        return false;
    }
    public function testGameByName()
    {
        $this->assertTrue($this->searchGameByName());
    }    


    // TEST findOpenPublicGamesByNameFuzzy()

    public function openPublicGamesByName()
    {
        $game = $this->gamesByName;
        // S'il trouve une partie avec p2secret égal à nul et sans mot de passe
        if(($game[0]->getName() === 'Recusandae asperiores accusamus nihil.') AND ($game[0]->getP2Secret() === null) AND ($game[0]->getPassword() === null)){
            return true;
        }
        return false;
    }
    public function testOpenPublicGamesByName()
    {
        $this->assertTrue($this->openPublicGamesByName());
    }
}