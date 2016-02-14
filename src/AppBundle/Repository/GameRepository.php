<?php

namespace AppBundle\Repository;

/**
 * GameRepository
 */
class GameRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Récupère la liste des parties en attente de joueur
     * @return array Liste des parties
     */
    public function findOpenGames()
    {
        return $this->findBy(array('p2Secret' => null));
    }

    /**
     * Récupère la liste des parties en attente de joueur et sans mot de passe
     * @return array Liste des parties
     */
    public function findOpenPublicGames()
    {
        return $this->findBy(array('p2Secret' => null,'password' => null));
    }

    /**
     * Récupère la liste des parties en attente de joueur et sans mot de passe
     * @return array Liste des parties
     */
    public function findGamesByNameFuzzy($name)
    {
        $query = $this->getEntityManager()->createQuery("SELECT g FROM AppBundle:Game g WHERE g.p2Secret IS NULL AND g.name like :searchterm")
            ->setParameter('searchterm', '%'.$name.'%');
        return $query->getResult();
    }

    /**
     * Récupère la liste des parties en attente de joueur sans mot de passe avec un nom donné
     * @return array Liste des parties
     */
    public function findOpenPublicGamesByNameFuzzy($name)
    {
        $query = $this->getEntityManager()->createQuery("SELECT g FROM AppBundle:Game g WHERE g.p2Secret IS NULL AND g.password IS NULL AND g.name like :searchterm")
            ->setParameter('searchterm', '%'.$name.'%');
        return $query->getResult();
    }
}
