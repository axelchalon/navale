<?php

namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * @Route("/api/v1")
 */
class ApiController extends Controller
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
     * @Get("/games", name="list_games")
     */
    public function listGamesAction()
    {
        return new Response();
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
     * @RequestParam(name="password", requirements=".+", nullable=true)
     * @Post("/games", name="create_game")
     */
    public function createGameAction(ParamFetcher $paramFetcher)
    {

        // BDD : insert & générer secret
        return new Response($paramFetcher->get('password'));
    }

    /**
     * @ApiDoc(
     *      description="Rejoint une partie.",
     *      section="1 - Parties",
     *      statusCodes={
     *          200: "Partie rejointe. Renvoie le secret du joueur."
     *      }
     * )
     * @Post("/games/{game_id}/players", name="join_game")
     */
    public function joinGameAction()
    {
        return new Response();
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
     * @Get("/games/{game_id}/players/2", name="player2_joined")
     */
    public function player2JoinedAction()
    {
        return new Response();
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
     *          400: "Les navires ne peuvent pas être placés tel que demandé. Les navires se croisent ou sortent du tableau."
     *      }
     * )
     * @Post("/games/{game_id}/ships", name="place_ships")
     */
    public function placeShipsAction()
    {
        return new Response();
    }

    /**
     * {ships: [{size: 4, x: 3, y:5, direction: 'horizontal' / 'vertical'}, {...}]}}
     *
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
     * @Get("/games/{game_id}/players/{player_id}/ships", name="playerX_placed_ships")
     */
    public function shipsPlacedAction()
    {
        return new Response();
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
     * @Post("/games/{game_id}/moves", name="shoot")
     */
    public function shootAction()
    {
        // tester si joueur est dans la room (token/compte), tester à qui le tour, tester si shot déjà joué
        return new Response();
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
     * @Get("/games/{game_id}/moves/{move_id}", name="retrieve_shot") ou /moves?
     */
    public function retrieveShotAction()
    {
        // tester si joueur est dans la room (token/compte), tester à qui le tour, tester si shot déjà joué
        return new Response();
    }
}