<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * Class GridController
 * @package Trinity\Bundle\GridBundle\Controller
 *
 * @Route("/grid")
 */
class GridController extends Controller
{

    /**
     * @Route("/{entity}/{query}", name="grid-index")
     */
    public function gridAction($entity, $query){
        $gridManager = $this->get('trinity.grid.manager');

        //$search = $this->get('trinity.search');
        //$entities = $search->query($query);

        /*  @todo - parse 2 parametr - > array of attributes
         *  @todo - Martin Matějka
         *
         */
        $arrayOfEntities = $gridManager->convertEntitiesToArray($entities, $query);

        return new JsonResponse($arrayOfEntities);
    }

}