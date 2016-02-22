<?php 
// src/AppBundle/Tests/Entity/GameTest.php
namespace AppBundle\Tests\Entity;

use AppBundle\Entity\Game;

class GameTest extends \PHPUnit_Framework_TestCase
{
    public function testName()
    {
        $jeu = new Game();
        $jeu->setName('Jeu 1');
        $nom = $jeu->getName();

        $this->assertEquals('Jeu 1', $nom);
    }

    public function testLengthGenerateP1Secret()
    {
    	$jeu = new Game();
    	$jeu->generateP1Secret();
    	$secret = $jeu->getP1Secret();
    	$this->assertEquals(20, strlen($secret));
    }
}