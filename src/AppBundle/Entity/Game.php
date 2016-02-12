<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Game
 *
 * @ORM\Table(name="game")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GameRepository")
 */
class Game
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="p1_secret", type="string", length=255)
     */
    private $p1Secret;

    /**
     * @var string
     *
     * @ORM\Column(name="p1_ships", type="string", length=255, nullable=true)
     */
    private $p1Ships;

    /**
     * @var string
     *
     * @ORM\Column(name="p1_shots_received", type="string", length=255, nullable=true)
     */
    private $p1ShotsReceived;

    /**
     * @var string
     *
     * @ORM\Column(name="p2_secret", type="string", length=255, nullable=true)
     */
    private $p2Secret;

    /**
     * @var string
     *
     * @ORM\Column(name="p2_ships", type="string", length=255, nullable=true)
     */
    private $p2Ships;

    /**
     * @var string
     *
     * @ORM\Column(name="p2_shots_received", type="string", length=255, nullable=true)
     */
    private $p2ShotsReceived;

    /**
     * @var string
     *
     * @ORM\Column(name="next_player", type="string", length=1, nullable=true)
     */
    private $nextPlayer;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Game
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Game
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set p1Secret
     *
     * @param string $p1Secret
     *
     * @return Game
     */
    public function setP1Secret($p1Secret)
    {
        $this->p1Secret = $p1Secret;

        return $this;
    }

    /**
     * Get p1Secret
     *
     * @return string
     */
    public function getP1Secret()
    {
        return $this->p1Secret;
    }

    /**
     * Set p1Ships
     *
     * @param string $p1Ships
     *
     * @return Game
     */
    public function setP1Ships($p1Ships)
    {
        $this->p1Ships = $p1Ships;

        return $this;
    }

    /**
     * Get p1Ships
     *
     * @return string
     */
    public function getP1Ships()
    {
        return $this->p1Ships;
    }

    /**
     * Set p1ShotsReceived
     *
     * @param string $p1ShotsReceived
     *
     * @return Game
     */
    public function setP1ShotsReceived($p1ShotsReceived)
    {
        $this->p1ShotsReceived = $p1ShotsReceived;

        return $this;
    }

    /**
     * Get p1ShotsReceived
     *
     * @return string
     */
    public function getP1ShotsReceived()
    {
        return $this->p1ShotsReceived;
    }

    /**
     * Set p2Secret
     *
     * @param string $p2Secret
     *
     * @return Game
     */
    public function setP2Secret($p2Secret)
    {
        $this->p2Secret = $p2Secret;

        return $this;
    }

    /**
     * Get p2Secret
     *
     * @return string
     */
    public function getP2Secret()
    {
        return $this->p2Secret;
    }

    /**
     * Set p2Ships
     *
     * @param string $p2Ships
     *
     * @return Game
     */
    public function setP2Ships($p2Ships)
    {
        $this->p2Ships = $p2Ships;

        return $this;
    }

    /**
     * Get p2Ships
     *
     * @return string
     */
    public function getP2Ships()
    {
        return $this->p2Ships;
    }

    /**
     * Set p2ShotsReceived
     *
     * @param string $p2ShotsReceived
     *
     * @return Game
     */
    public function setP2ShotsReceived($p2ShotsReceived)
    {
        $this->p2ShotsReceived = $p2ShotsReceived;

        return $this;
    }

    /**
     * Get p2ShotsReceived
     *
     * @return string
     */
    public function getP2ShotsReceived()
    {
        return $this->p2ShotsReceived;
    }

    /**
     * Set nextPlayer
     *
     * @param string $nextPlayer
     *
     * @return Game
     */
    public function setNextPlayer($nextPlayer)
    {
        $this->nextPlayer = $nextPlayer;

        return $this;
    }

    /**
     * Get nextPlayer
     *
     * @return string
     */
    public function getNextPlayer()
    {
        return $this->nextPlayer;
    }
}

