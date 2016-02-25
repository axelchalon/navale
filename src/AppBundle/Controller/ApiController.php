<?php

namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Component\HttpKernel\Exception\HttpException;

use AppBundle\Entity\Game;

/**
 * @Route("/")
 */
class ApiController extends FOSRestController
{
    /**
     * @Route("/", name="api_homepage")
     */
    public function indexAction(Request $request)
    {
        return new Response(); // @TODO List available endpoints or link to API doc.
    }

    /**
     * ===========
     * PARTIES
     * ===========
     **/

    /**
     * @ApiDoc(
     *      description="Liste les parties en attente de joueur",
     *      section="1 - Parties"
     * )
     * @QueryParam(name="type", requirements="public", nullable=true, description="Filtrer les parties par type.")
     * @QueryParam(name="name", requirements=".+", nullable=true, description="Filtrer les parties par nom.")
     * @Get("/games", name="list_games")
     * @Get("/api/v1/games", name="list_games_api")
     */
    public function listGamesAction(ParamFetcher $paramFetcher)
    {
        $em = $this->getDoctrine()->getManager();
        
        if ($paramFetcher->get('type') === 'public' && $paramFetcher->get('name') === null)
            $games = $em->getRepository('AppBundle:Game')->findOpenPublicGames();
        else if ($paramFetcher->get('type') === 'public' && $paramFetcher->get('name') !== null)
            $games = $em->getRepository('AppBundle:Game')->findOpenPublicGamesByNameFuzzy($paramFetcher->get('name'));
        else if ($paramFetcher->get('name') !== null)
            $games = $em->getRepository('AppBundle:Game')->findGamesByNameFuzzy($paramFetcher->get('name'));
        else
            $games = $em->getRepository('AppBundle:Game')->findOpenGames();

        return $this->view($games)
                    ->setTemplate("AppBundle:Api:list_games.html.twig")
                    ->setTemplateData(array('games' => $games));
    }

    /**
     * Return Location: /api/v1/rooms/12 (ou HATEOAS)
     * @ApiDoc(
     *      description="Crée une partie.",
     *      section="1 - Parties",
     *      statusCodes={
     *          201: "Partie créée. Renvoie son ID et le secret du joueur."
     *      }
     * )
     * @RequestParam(name="password", nullable=true, description="Mot de passe de la partie")
     * @RequestParam(name="name", requirements=".+", allowBlank=false, strict=true, description="Nom de la partie")
     * @Post("/api/v1/games", name="create_game_api")
     * @Post("/games", name="create_game")
     */
    public function createGameAction(ParamFetcher $paramFetcher)
    {
        $game = new Game;
        $game->setName($paramFetcher->get('name'));
        $game->generateP1Secret();
        $game->setPassword($paramFetcher->get('password'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($game);
        $em->flush();

        return $this->view(array('game_id'=>$game->getId(), 'secret' => $game->getP1Secret()))
                    ->setTemplate('AppBundle:Api:create_game.html.twig')
                    ->setTemplateData(array('game' => $game));
    }

    /**
     * @ApiDoc(
     *      description="Rejoint une partie.",
     *      section="1 - Parties",
     *      statusCodes={
     *          200: "Partie rejointe. Renvoie le secret du joueur.",
     *          403: "Mauvais mot de passe pour rejoindre la partie, ou bien la partie est déjà remplie."
     *      }
     * )
     * @RequestParam(name="password", nullable=true, description="Mot de passe de la partie")
     * @Post("/api/v1/games/{game_id}/players", name="join_game_api")
     * @Post("/games/{game_id}/players", name="join_game")
     * @ParamConverter("game", class="AppBundle:Game", options={"id" = "game_id"})
     */
    public function joinGameAction(Game $game, ParamFetcher $paramFetcher)
    {
        if ($game->getPassword() !== $paramFetcher->get('password'))
            throw new HttpException(400, 'Bad password.');

        if ($game->isFull())
            throw new HttpException(400, 'Game is already full.');

        $game->generateP2Secret();

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($game);
        $em->flush();

        return $this->view(array('secret' => $game->getP2Secret()))
                    ->setTemplate('AppBundle:Api:player_join.html.twig')
                    ->setTemplateData(array('game' => $game, 'secret' => $game->getP2Secret()));
    }

    /**
     * ===========
     * ATTENTE JOUEUR
     * ===========
     **/

    /**
     * @ApiDoc(
     *      description="Indique si quelqu'un a rejoint la partie.",
     *      section="2 - Attente d'adversaire",
     *      authentication=true,
     *      statusCodes={
     *          200: "Quelqu'un a rejoint la partie. Il faut maintenant placer ses navires.",
     *          404: "Pas encore d'adversaire.",
     *      }
     * )
     * @ParamConverter("game", class="AppBundle:Game", options={"id" = "game_id"})
     * @Get("/games/{game_id}/players/2", name="player2_joined")
     * @Get("/api/v1/games/{game_id}/players/2", name="player2_joined_api")
     */
    public function player2JoinedAction(Game $game)
    {
        if (!$game->isFull()){

            return $this->view(array('error' => 'No one has joined yet.'),404)
                        ->setTemplate('AppBundle:Api:join.html.twig');
        }

        return $this->view(array('info' => 'Somebody joined!'),200)
                        ->setTemplate('AppBundle:Api:no_player_join.html.twig');
    }

    /**
     * ===========
     * NAVIRES
     * ===========
     **/

    /**
     * {ships: [{size: 4, x: 3, y:5, direction: 'horizontal' / 'vertical'}, {...}]}}
     *
     * return {valid: true} ou { valid: false, reason: 'Illegal : two ships intersecting'}
     * @ApiDoc(
     *      description="Place ses navires.",
     *      section="3 - Placement des navires",
     *      authentication=true,
     *      statusCodes={
     *          200: "Les navires ont été placés. Il faut maintenant attendre que l'autre joueur ait fait de même.",
     *          400: "Les navires ne peuvent pas être placés tel que demandé. Les navires se croisent ou sortent du tableau.",
     *          403: "Mauvais secret."
     *      }
     * )
     * @ParamConverter("game", class="AppBundle:Game", options={"id" = "game_id"})
     * @RequestParam(name="secret", nullable=false, description="Secret du joueur")
     * @RequestParam(name="ships", array=true, nullable=false, description="Tableau de {x, y, size, direction <horizontal|vertical>}")
     * @Post("/api/v1/games/{game_id}/ships", name="place_ships_api")
     * @Post("/games/{game_id}/ships", name="place_ships")
     */
    public function placeShipsAction(Game $game, ParamFetcher $paramFetcher)
    {
        $player = $game->getPlayerBySecret($paramFetcher->get('secret'));
        // @TODO Exception in Game entity if bad secret?
        if ($player === null)
            throw new HttpException(400, 'Bad secret.');

        if (!$game->isFull()) // @todo mettre ces vérifs dans l'entité
            throw new HttpException(400, 'No one has joined yet.');

        if ($game->playerHasPlacedShips($player))
            throw new HttpException(400, 'You\'ve already placed your ships.');

        if (!($res = $game->setPlayerShips($player,$paramFetcher->get('ships'))) instanceof Game) // can throw exception @TODO
            var_dump($res);

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($game);
        $em->flush();

        return $this->view(array('info' => 'Ships placed!'),200)
                    ->setTemplate('AppBundle:Api:verify_placed_ships.html.twig')
                    ->setTemplateData(array('game' => $game, 'opponent_player_id' => $player == 1 ? 2 : 1));
    }

    /**
     * return 404 {ready: false} ou 200 {ready: true}
     * @ApiDoc(
     *      description="Indique si l'adversaire a placé ses navires.",
     *      section="3 - Placement des navires",
     *      authentication=true,
     *      statusCodes={
     *          200: "L'adversaire a placé ses navires.",
     *          404: "L'adversaire n'a pas encore placé ses navires."
     *      }
     * )
     * @ParamConverter("game", class="AppBundle:Game", options={"id" = "game_id"})
     * @Get("/api/v1/games/{game_id}/players/{player_id}/ships", name="playerX_placed_ships_api")
     * @Get("/games/{game_id}/players/{player_id}/ships", name="playerX_placed_ships")
     */
    public function shipsPlacedAction(Game $game, $player_id)
    {
        if (!$game->playerHasPlacedShips($player_id))
        {
            return $this->view(array('error' => 'The player\'s ships haven\'t been placed yet.'),404)
                    ->setTemplate('AppBundle:Api:verify_placed_ships.html.twig')
                    ->setTemplateData(array('game' => $game, 'opponent_player_id' => $player_id));
        }

        return $this->view(array('info' => 'The player\'s ships have been placed.'),200);
    }

    /**
     * ===========
     * COUPS
     * ===========
     **/

    /**
     * {coord: {y: 12, x: 13}}
     *
     * return {valid: false, reason: 'Already fired there. Result was a miss.'} ou {valid: true, move_id: X, result: 'miss/hit/sank', ??goban: {}?? }
     * @ApiDoc(
     *      description="Joue un coup.",
     *      section="4 - Jeu",
     *      authentication=true,
     *      statusCodes={
     *          200: "Le coup a été joué. Renvoie le résultat du coup (manqué/touché/coulé).",
     *          400: "Le coup n'est pas valide, ou bien ce n'est pas à votre tour de jouer."
     *      }
     * )
     *
     * @RequestParam(name="secret", nullable=false, description="Secret du joueur")
     * @RequestParam(name="x", nullable=false, description="Position X du tir")
     * @RequestParam(name="y", nullable=false, description="Position Y du tir")
     * @ParamConverter("game", class="AppBundle:Game", options={"id" = "game_id"})
     * @Post("/api/v1/games/{game_id}/moves", name="shoot_api")
     * @Post("/games/{game_id}/moves", name="shoot")
     */
    public function shootAction(Game $game, ParamFetcher $paramFetcher)
    {
        $player = $game->getPlayerBySecret($paramFetcher->get('secret'));

        if ($player === null)
            throw new HttpException(400, 'Bad secret.');

        if (!$game->isFull())
            throw new HttpException(400, 'The game isn\'t full yet.');

        if ($game->isGameOver())
            throw new HttpException(400, 'Game over.');

        if ($game->getNextPlayer() === null || (int)$game->getNextPlayer() !== (int)$player)
            throw new HttpException(400, 'It\'s not your turn to play.');

        $result = $game->playerShoots($player,['x' => $paramFetcher->get('x'), 'y' => $paramFetcher->get('y')]); // can throw exception @TODO
        if (is_int($result))
            exit('Erreur : ' . $result);

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($game);
        $em->flush();

        return $this->view(array('result' => $result),200);
    }

    /**
     *
     * return 404 { status: 'pending' } ou 200 {coord: {y: 12, x: 13}}
     * @ApiDoc(
     *      description="Récupère les informations sur le coup de l'adversaire.",
     *      section="4 - Jeu",
     *      authentication=true,
     *      statusCodes={
     *          200: "L'adversaire a joué son coup. Renvoie les informations sur le coup (case visée).",
     *          404: "L'adversaire n'a pas encore joué ce coup."
     *      }
     * )
     * @ParamConverter("game", class="AppBundle:Game", options={"id" = "game_id"})
     * @Get("/api/v1/games/{game_id}/moves/{move_id}", name="retrieve_shot_api") ou /moves?
     * @Get("/games/{game_id}/moves/{move_id}", name="retrieve_shot") ou /moves?
     */
    public function retrieveShotAction(Game $game, $move_id, ParamFetcher $paramFetcher)
    {
        $move = $game->retrieveShot($move_id);
        return $this->view(array('move' => $move),200);
    }
}