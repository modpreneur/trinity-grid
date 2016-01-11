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

        $search = $this->get('trinity.search');

        $nqlQuery = $search->queryTable($entity, $query);

        $columns = [];

        foreach($nqlQuery->getSelect()->getColumns() as $column) {
            $columns[] = $column->getName();
        }

        $arrayOfEntities = $gridManager->convertEntitiesToArray($nqlQuery->getQueryBuilder()->getQuery()->getResult(), $columns);

        return new JsonResponse($arrayOfEntities);
    }

}