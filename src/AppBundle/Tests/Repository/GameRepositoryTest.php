<?php
// src/AppBundle/Tests/Repository/GameRepositoryTest.php

namespace AppBundle\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameRepositoryTest extends WebTestCase
{
    private $openGames;
    private $openPublicGames;
    private $gamesByName;

    public function setUp()
    {  
        $kernel = static::createKernel();
        $kernel->boot();

        $em = $kernel->getContainer()->get('doctrine.orm.entity_manager')->getRepository('AppBundle:Game');

        // Accède à la méthode findOpenGames() du répository GameRepository
        $this->openGames = $em->findOpenGames();

        // méthode findOpenPublicGames()
        $this->openPublicGames = $em->findOpenPublicGames();

        // méthode findGamesByNameFuzzy()
        $this->gamesByName = $em->findGamesByNameFuzzy('exemple');
    }

    // TEST findOpenGames()
    public function testFindOpenGames()
    {
        foreach ($this->openGames as $game)
        {
            $this->assertTrue($game->getP2Secret() === null);
        }
    }

    // TEST findOpenPublicGames()
    public function testFindOpenPublicGames()
    {
        $games = $this->openPublicGames;
        foreach ($games as $value)
        {   // Si le joueur 2 possède un secret ou que la partie a un mot de passe, on return FALSE
            $this->assertTrue($value->getP2Secret() === null AND $value->getPassword() === null);
        }
    }

    // TEST findGamesByNameFuzzy()
    public function testFindGamesByNameFuzzy()
    {
        // S'il trouve une partie avec p2secret égal à nul
        $this->assertTrue(($this->gamesByName[0]->getName() === 'Exemple de partie') AND ($this->gamesByName[0]->getP2Secret() === null));
    }

    // TEST findOpenGamesByNameFuzzy()
    public function testFindOpenGamesByNameFuzzy()
    {
        // S'il trouve une partie avec p2secret égal à nul
        $this->assertTrue(($this->gamesByName[0]->getName() === 'Exemple de partie') AND ($this->gamesByName[0]->getP2Secret() === null && ($this->gamesByName[0]->getPassword() === null)));
    }

}