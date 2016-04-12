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
 * @Route("/grid", defaults={"_format": "json"})
 */
class GridController extends Controller
{

    /**
     * @Route("/elastic/{entity}/{query}", name="grid-elastic")
     * @param string $entity
     * @param string $query
     * @return JsonResponse
     * @throws \Trinity\Bundle\GridBundle\Exception\InvalidArgumentException
     */
    public function gridElasticAction($entity, $query){

        $gridManager = $this->get('trinity.grid.manager');

        $search = $this->get('trinity.search');

        $nqlQuery = $search->queryTable($entity, $query);

        $columns = [];

        foreach($nqlQuery->getSelect()->getColumns() as $column) {
            $columns[] = $column->getFullName();
        }

        $entities = $this->get('trinity.logger.elastic.read.log.service')->getByQuery($nqlQuery);


        $arrayOfEntities = $gridManager->convertEntitiesToArray($search, $entities, $columns);

        return new JsonResponse($arrayOfEntities);
    }


    /**
     * @Route("/{entity}/{query}", name="grid-index")
     * @param string $entity
     * @param string $query
     * @return JsonResponse
     * @throws \Trinity\Bundle\GridBundle\Exception\InvalidArgumentException
     */
    public function gridAction($entity, $query){

        $gridManager = $this->get('trinity.grid.manager');

        $search = $this->get('trinity.search');

        $nqlQuery = $search->queryTable($entity, $query);

        $columns = [];

        foreach($nqlQuery->getSelect()->getColumns() as $column) {
            $columns[] = $column->getFullName();
        }

        $arrayOfEntities = $gridManager->convertEntitiesToArray($search, $nqlQuery->getQueryBuilder(true)->getQuery()->getResult(), $columns);

        return new JsonResponse($arrayOfEntities);
    }




}