<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\Controller;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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
class GridController extends FOSRestController
{

    /**
     * @Route("/elastic/{entity}", name="grid-elastic")
     *
     * @QueryParam(name="q", nullable=false, strict=true, description="DB Query", allowBlank=true)
     *
     * @param string $entity
     * @param ParamFetcher $paramFetcher
     *
     * @return JsonResponse
     *
     * @throws \Trinity\Component\Utils\Exception\MemberAccessException
     * @throws \Trinity\Bundle\SearchBundle\Exception\SyntaxErrorException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Trinity\Bundle\GridBundle\Exception\InvalidArgumentException
     */
    public function gridElasticAction(ParamFetcher $paramFetcher, $entity)
    {
        $query = $paramFetcher->get('q');

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
     * @Route("/{entity}", name="grid-index")
     *
     * @QueryParam(name="c", nullable=true, strict=true, description="Columns", allowBlank=false)
     * @QueryParam(name="q", nullable=false, strict=true, description="DB Query", allowBlank=true)
     * @QueryParam(name="offset", nullable=true, strict=true, description="Offset", allowBlank=false)
     * @QueryParam(name="limit", nullable=true, strict=true, description="Limit", allowBlank=false)
     * @QueryParam(name="orderBy", nullable=true, strict=true, description="Order by", allowBlank=false)
     *
     * @param ParamFetcher $paramFetcher
     * @param string $entity
     * @return JsonResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Trinity\Component\Utils\Exception\MemberAccessException
     * @throws \Trinity\Bundle\SearchBundle\Exception\SyntaxErrorException
     * @throws \Trinity\Bundle\GridBundle\Exception\InvalidArgumentException
     */
    public function gridAction(ParamFetcher $paramFetcher, $entity)
    {
        $query = $paramFetcher->get('q');
        $queryColumns = $paramFetcher->get('c');
        $offset = $paramFetcher->get('offset');
        $limit = $paramFetcher->get('limit');
        $orderBy = $paramFetcher->get('orderBy');

        /** @var GridManager $gridManager */
        $gridManager = $this->get('trinity.grid.manager');

        /** @var Search $search */
        $search = $this->get('trinity.search');

        if ($queryColumns === null) {
            /** @var NQLQuery $nqlQuery */
            $nqlQuery = $search->queryTable($entity, $query);
        } else {
            /** @var NQLQuery $nqlQuery */
            $nqlQuery = $search->queryEntity($entity, $queryColumns, null, $query, $limit, $offset, $orderBy);
        }

        $gridManager->getGrid($entity)->prepareQuery($nqlQuery);

        $columns = [];

        /** @var Column $column */
        foreach ($nqlQuery->getSelect()->getColumns() as $column) {
            $columns[] = $column->getFullName();
        }

        $queryBuilder = $nqlQuery->getQueryBuilder(true);

        $result = $gridManager->convertEntitiesToArray(
            $queryBuilder->getQuery()->getResult(),
            $columns
        );

        $totalCount = $this->get('trinity.search.dql_converter')->convertToCount($queryBuilder)->getQuery()->getSingleScalarResult();

        return new JsonResponse(
            ['count' => ['result' => count($result), 'total' => $totalCount], 'result' => $result]
        );
    }
}
