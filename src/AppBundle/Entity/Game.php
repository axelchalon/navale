<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\SerializedName;


use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\ExclusionPolicy;


/**
 * Game
 * @ORM\Table(name="game")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GameRepository")
 *
 * @ExclusionPolicy("all")
 */
class Game
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     * @Expose
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var string
     * @ORM\Column(name="p1_secret", type="string", length=255)
     */
    private $p1Secret;

    /**
     * @TODO s'assurer que la reconstruction avec doctrine:schema:update crée une colonne de type "text"
     * @var string
     * @ORM\Column(name="p1_ships", type="json_array", length=255, nullable=true)
     */
    private $p1Ships;

    /**
     * @var string
     * @ORM\Column(name="p1_shots_received", type="string", length=255, nullable=true)
     */
    private $p1ShotsReceived;

    /**
     * @var string
     * @ORM\Column(name="p2_secret", type="string", length=255, nullable=true)
     */
    private $p2Secret;

    /**
     * @var string
     * @ORM\Column(name="p2_ships", type="json_array", length=255, nullable=true)
     */
    private $p2Ships;

    /**
     * @var string
     * @ORM\Column(name="p2_shots_received", type="string", length=255, nullable=true)
     */
    private $p2ShotsReceived;

    /**
     * @var string
     * @ORM\Column(name="next_player", type="string", length=1, nullable=true)
     */
    private $nextPlayer;

    /**
     * Get id
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     * @param string $name
     * @return Game
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set password
     * @param string $password
     * @return Game
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get password
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @VirtualProperty()
     */
    public function hasPassword()
    {
        return $this->getPassword() !== null;
    }


    /**
     * Generate secret
     * @return Secret
     */
    private function generateSecret()
    {
        return bin2hex(random_bytes(10));
    }

    /**
     * Generate p1Secret
     * @return Game
     */
    public function generateP1Secret()
    {
        // @TODO Throw exception if $this->p1Secret is not null
        $this->p1Secret = $this->generateSecret();
        return $this;
    }

    /**
     * Get p1Secret
     * @return string
     */
    public function getP1Secret()
    {
        return $this->p1Secret;
    }

    /**
     * Set p1Ships
     * @param string $p1Ships
     * @return Game
     */
    /*private function setP1Ships($p1Ships)
    {
        $this->p1Ships = $p1Ships;
        return $this;
    }*/

    /**
     * Get p1Ships
     * @return string
     */
    public function getP1Ships()
    {
        return $this->p1Ships;
    }

    /**
     * Whether the player has already placed his ships
     * @return bool
     */
    public function playerHasPlacedShips($player)
    {
        if ($player == 1)
            return !empty($this->p1Ships);
        else if ($player == 2)
            return !empty($this->p2Ships);
        // else, exception @TODO
    }

    /**
     * Place player's ships
     * @return Game
     */
    public function setPlayerShips($player,$ships)
    {
        $shipsPresets = [5,4,3,3,2]; // sizes
        $occupiedPositions = []; // {x: ?, y: ?}

        foreach ($ships as $ship)
        {
            if (!isset($ship['size']))
                return 1; // @TODO throw exception

            if(($key = array_search((int)$ship['size'], $shipsPresets)) === false)
                return 2; // @TODO throw exception

            if(sizeof(array_keys($ship)) !== 4) // x, y, size, orientation
                return 3; // @TODO throw exception

            unset($shipsPresets[$key]);

            if (!isset($ship['orientation']) || (!in_array($ship['orientation'],array('horizontal', 'vertical'))))
                return 4; // @TODO throw exception

            if ($ship['orientation'] == 'horizontal')
            {
                $xp = 1;
                $yp = 0;
            }
            else
            {
                $xp = 0;
                $yp = 1;
            }

            for (
                $x = (int)$ship['x'], $y = (int)$ship['y'], $remainingSize = (int)$ship['size'];
                $remainingSize > 0;
                $x+=$xp, $y+=$yp, $remainingSize--)
            {
                if ($x < 0 || $y < 0 || $x > 9 || $y > 9)
                    return 5; // @TODO throw exception

                $newPosition = ['x' => $x, 'y' => $y];

                if (in_array($newPosition,$occupiedPositions)) // feature idea @todo : dire quels navires s'intersectent
                    return 6; // @TODO throw exception (intersection)

                $occupiedPositions[] = $newPosition;
            }

        }

        if ($player == 1)
            $this->p1Ships = $ships;
        else if ($player == 2)
            $this->p2Ships = $ships;
        // else, exception @TODO



        return $this;
    }

    /**
     * Set p1ShotsReceived
     * @param string $p1ShotsReceived
     * @return Game
     */
    public function setP1ShotsReceived($p1ShotsReceived)
    {
        $this->p1ShotsReceived = $p1ShotsReceived;
        return $this;
    }

    /**
     * Get p1ShotsReceived
     * @return string
     */
    public function getP1ShotsReceived()
    {
        return $this->p1ShotsReceived;
    }

    /**
     * Generate p2Secret
     * @return Game
     */
    public function generateP2Secret()
    {
        // @TODO Throw exception if $this->p2Secret is not null
        $this->p2Secret = $this->generateSecret();
        return $this;
    }

    /**
     * Get p2Secret
     * @return string
     */
    public function getP2Secret()
    {
        return $this->p2Secret;
    }

    /**
     * Get player number corresponding to given secret
     * @return int
     */
    public function getPlayerBySecret($secret)
    {
        if ($this->getP1Secret() == $secret)
            return 1;
        else if ($this->getP2Secret() == $secret)
            return 2;
        else
            return null;
    }

    /**
     * Whether two players are already in the game or not
     * @return bool
     */
    public function isFull()
    {
        return $this->p2Secret !== null;
    }

    /**
     * Set p2Ships
     * @param string $p2Ships
     * @return Game
     */
    /*private function setP2Ships($p2Ships)
    {
        $this->p2Ships = $p2Ships;
        return $this;
    }*/

    /**
     * Get p2Ships
     * @return string
     */
    public function getP2Ships()
    {
        return $this->p2Ships;
    }

    /**
     * Set p2ShotsReceived
     * @param string $p2ShotsReceived
     * @return Game
     */
    public function setP2ShotsReceived($p2ShotsReceived)
    {
        $this->p2ShotsReceived = $p2ShotsReceived;
        return $this;
    }

    /**
     * Get p2ShotsReceived
     * @return string
     */
    public function getP2ShotsReceived()
    {
        return $this->p2ShotsReceived;
    }

    /**
     * Set nextPlayer
     * @param string $nextPlayer
     * @return Game
     */
    public function setNextPlayer($nextPlayer)
    {
        $this->nextPlayer = $nextPlayer;
        return $this;
    }

    /**
     * Get nextPlayer
     * @return string
     */
    public function getNextPlayer()
    {
        return $this->nextPlayer;
    }
}

