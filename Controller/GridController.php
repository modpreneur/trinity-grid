<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Trinity\Bundle\GridBundle\Grid\GridManager;
use Trinity\Bundle\SearchBundle\NQL\Column;
use Trinity\Bundle\SearchBundle\NQL\NQLQuery;
use Trinity\Bundle\SearchBundle\Search;

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
     *
     * @param string $entity
     * @param string $query
     *
     * @return JsonResponse
     *
     * @throws \Trinity\FrameworkBundle\Exception\MemberAccessException
     * @throws \Trinity\Bundle\SearchBundle\Exception\SyntaxErrorException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Trinity\Bundle\GridBundle\Exception\InvalidArgumentException
     */
    public function gridElasticAction($entity, $query)
    {
        /** @var GridManager $gridManager */
        $gridManager = $this->get('trinity.grid.manager');

        /** @var Search $search */
        $search = $this->get('trinity.search');

        /** @var NQLQuery $nqlQuery */
        $nqlQuery = $search->queryTable($entity, $query);

        $columns = [];

        foreach ($nqlQuery->getSelect()->getColumns() as $column) {
            $columns[] = $column->getFullName();
        }

        $entities = $this->get('trinity.logger.elastic_read_log_service')->getByQuery($nqlQuery);

//        return new JsonResponse(
//            $gridManager->convertEntitiesToArray($entities, $columns)
//        );

        $result = $gridManager->convertEntitiesToArray($entities, $columns);

        //Gabi-TODO: when search/query/WHERE part is done solve this
        return new JsonResponse(
            ['count' => ['result' => count($result), 'total' => count($result)], 'result' => $result]
        );
        
    }


    /**
     * @Route("/{entity}/{query}", name="grid-index")
     *
     * @param string $entity
     * @param string $query
     *
     * @return JsonResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     *
     * @throws \Trinity\FrameworkBundle\Exception\MemberAccessException
     * @throws \Trinity\Bundle\SearchBundle\Exception\SyntaxErrorException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Trinity\Bundle\GridBundle\Exception\InvalidArgumentException
     */
    public function gridAction($entity, $query)
    {
        /** @var GridManager $gridManager */
        $gridManager = $this->get('trinity.grid.manager');

        /** @var Search $search */
        $search = $this->get('trinity.search');

        /** @var NQLQuery $nqlQuery */
        $nqlQuery = $search->queryTable($entity, $query);

        $columns = [];

        /** @var Column $column */
        foreach ($nqlQuery->getSelect()->getColumns() as $column) {
            $columns[] = $column->getFullName();
        }

        $result = $gridManager->convertEntitiesToArray(
            $nqlQuery->getQueryBuilder(true)->getQuery()->getResult(),
            $columns
        );

        $totalCount = $this->get('trinity.search.dql_converter')->count($entity)->getQuery()->getSingleScalarResult();

        return new JsonResponse(
            ['count' => ['result' => count($result), 'total' => $totalCount], 'result' => $result]
        );
    }
}
