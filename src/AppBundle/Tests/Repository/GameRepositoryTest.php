<?php
// src/Blogger/BlogBundle/Tests/Repository/GameRepositoryTest.php

namespace AppBundle\Tests\Repository;

use AppBundle\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameRepositoryTest extends WebTestCase
{
    /**
     * @var \AppBundle\Repository\GameRepository
     */
    private $GameRepository;

    public function setUp()
    {  
        // Accède à la méthode findOpenGames() du répository GameRepository
        $kernel = static::createKernel();
        $kernel->boot();
        $this->GameRepository = $kernel->getContainer()
                                       ->get('doctrine.orm.entity_manager')
                                       ->getRepository('AppBundle:Game')->findOpenGames();
    }

    public function openGames()
    {
        $games = $this->GameRepository;

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

    public function testOpenGames2()
    {   
        $result = $this->openGames();
        
        // Vérifie si aucun joueur 2 ne possède de mot de passe
        $this->assertTrue($result);
    }
/*
    public function testGetTags()
    {
        $tags = $this->AppRepository->getTags();

        $this->assertTrue(count($tags) > 1);
        $this->assertContains('symblog', $tags);
    }

    public function testGetTagWeights()
    {
        $tagsWeight = $this->AppRepository->getTagWeights(
            array('php', 'code', 'code', 'symblog', 'blog')
        );

        $this->assertTrue(count($tagsWeight) > 1);

        // Test case where count is over max weight of 5
        $tagsWeight = $this->AppRepository->getTagWeights(
            array_fill(0, 10, 'php')
        );

        $this->assertTrue(count($tagsWeight) >= 1);

        // Test case with multiple counts over max weight of 5
        $tagsWeight = $this->AppRepository->getTagWeights(
            array_merge(array_fill(0, 10, 'php'), array_fill(0, 2, 'html'), array_fill(0, 6, 'js'))
        );

        $this->assertEquals(5, $tagsWeight['php']);
        $this->assertEquals(3, $tagsWeight['js']);
        $this->assertEquals(1, $tagsWeight['html']);

        // Test empty case
        $tagsWeight = $this->AppRepository->getTagWeights(array());

        $this->assertEmpty($tagsWeight);
    }*/
}