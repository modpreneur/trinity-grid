<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\Grid;

use Trinity\Bundle\GridBundle\Exception\InvalidArgumentException;
use Trinity\Bundle\GridBundle\Filter\GridFilterInterface;
use Trinity\FrameworkBundle\Exception\MemberAccessException;
use Trinity\FrameworkBundle\Utils\ObjectMixin;


/**
 * Class GridManager
 * @package Trinity\Grid
 */
class GridManager
{
    /**
     * @var array
     */
    protected $grids;


    /** @var  GridFilterInterface[] */
    protected $filter = [];


    /**
     * GridManager constructor.
     */
    public function __construct()
    {
        $this->grids = [];
    }


    /**
     * @param string $alias
     * @param BaseGrid $grid
     *
     * @return GridManager
     */
    public function addGrid($alias, $grid) : GridManager
    {
        $this->grids[$alias] = $grid;

        return $this;
    }


    /**
     * @return array
     */
    public function getGrids() : array
    {
        return $this->grids;
    }


    /**
     * @param String $name
     * @return BaseGrid|null
     */
    public function getGrid($name) : BaseGrid
    {
        if (array_key_exists($name, $this->grids)) {
            return $this->grids[$name];
        }

        return null;
    }


    /**
     * @param array|\Iterator $entities
     * @param array $columns
     *
     * @return array
     * @throws InvalidArgumentException
     * @throws MemberAccessException
     */
    public function convertEntitiesToArray($search, $entities, $columns) : array
    {
        if (!$this->is_iterable($entities)) {
            throw new InvalidArgumentException('Agrument \'entities\' is not iterable.');
        }

        $grid = $this->getGrid(
            $this->getGridNameFromEntieies($entities)
        );


        $arrayResult = [];

        foreach ($entities as $entity) {

            $row = [];
            foreach ($columns as $column) {

//                $value = null;
//
//                try {
//                    if (is_object($entity)) {
//                        $value = ObjectMixin::get($entity, $column);
//                    } elseif (is_array($entity)) {
//                        $value = $entity[$column];
//                    } else {
//                        throw new InvalidArgumentException("Wrong format for entities.");
//                    }
//
//                } catch (MemberAccessException $ex) {
//                    $value = "";
//                }

                $value = $search->getValue($entity, $column);

                // specific filter
                $filter = $grid->getColumnFormat($column);
                if (!empty($filter)) {
                    $filter = $this->getFilter($filter);
                    $value = $filter->process($value, ['column' => $column, 'entity' => $entity, 'grid' => $grid]);
                }

                /** @var GridFilterInterface[] $filters */
                $filters = $this->getGlobalFilters();
                foreach ($filters as $filter) {
                    $value = $filter->process($value, ['column' => $column, 'entity' => $entity, 'grid' => $grid]);
                }


                $row[$column] = $value;
            }

            $arrayResult[] = $row;
        }

        return $arrayResult;
    }


    /**
     * @param array $entities
     * @return string
     * @throws InvalidArgumentException
     */
    public function getGridNameFromEntieies($entities): string
    {
        /* Get name */
        $first = reset($entities);

        if (!is_object($first)) {
            throw new InvalidArgumentException("Entities must be array of entities (array of objects).");
        }

        $rc = new \ReflectionClass($first);
        $name = strtolower($rc->getShortName());

        return $name;
    }


    /**
     * @param object|[] $var
     * @return bool
     */
    function is_iterable($var) : bool
    {
        return (is_array($var) || $var instanceof \Traversable);
    }


    /**
     *
     * @param GridFilterInterface $gridFilterInterface
     * @return GridManager
     */
    public function addFilter(GridFilterInterface $gridFilterInterface) : GridManager
    {
        if(!empty($gridFilterInterface->getName()))
            $this->filter[$gridFilterInterface->getName()] = $gridFilterInterface;
        else
            $this->filter[] = $gridFilterInterface;

        return $this;
    }


    /**
     * @param $column
     * @return GridFilterInterface
     */
    public function getFilter($column) : GridFilterInterface
    {
        if (array_key_exists($column, $this->filter)) {
            return $this->filter[$column];
        }

        throw new \BadFunctionCallException("Filter '$column' does not exists.");
    }


    /**
     * @return array
     */
    public function getGlobalFilters()
    {
        return array_filter(
            $this->filter,
            function (GridFilterInterface $v) {
                return $v->isGlobal();
            }
        );
    }

}