<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\Grid;

use Trinity\Bundle\GridBundle\Exception\InvalidArgumentException;
use Trinity\Bundle\GridBundle\Filter\FilterInterface;
use Trinity\Component\Utils\Exception\MemberAccessException;
use Trinity\Component\Utils\Utils\ObjectMixin;

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


    /** @var  FilterInterface[] */
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
    public function convertEntitiesToArray($entities, $columns) : array
    {
        if (!$this->isIterable($entities)) {
            throw new InvalidArgumentException('Argument \'entities\' is not iterable.');
        }

        if (count($entities) > 0) {
            $grid = $this->getGrid(
                $this->getGridNameFromEntities($entities)
            );
        }

        $arrayResult = [];

        foreach ($entities as $entity) {
            $row = [];
            foreach ($columns as $column) {
                $value = self::getValue($entity, $column);

                // $grid is defined in condition count of entities > 0 so always in foreach entities
                // specific filter
                $filter = $grid->getColumnFormat($column);
                if (!empty($filter)) {
                    $filter = $this->getFilter($filter);
                    $value = $filter->process(
                        $value,
                        ['column' => str_replace('.', '_', $column), 'entity' => $entity, 'grid' => $grid]
                    );
                }

                /** @var FilterInterface[] $filters */
                $filters = $this->getGlobalFilters();

                foreach ($filters as $filter) {
                    $value = $filter->process(
                        $value,
                        ['column' => str_replace('.', '_', $column),'entity' => $entity, 'grid' => $grid]
                    );
                }

                $row[preg_replace('/\./', ':', $column)] = $value;
            }

            $arrayResult[] = $row;
        }

        return $arrayResult;
    }


    /**
     * @param object $entity
     * @param string $value
     * @return mixed|string
     */
    public static function getValue($entity, $value)
    {
        $values = explode('.', $value);
        return self::getObject($entity, $values, 0);
    }

    /**
     * @param object $entity
     * @param array $values
     * @param int $curValueIndex
     * @return mixed|string
     */
    private static function getObject($entity, $values, $curValueIndex)
    {
        try {
            $obj = ObjectMixin::get($entity, $values[$curValueIndex]);
            if ($curValueIndex === count($values) - 1) {
                return $obj;
            } elseif (is_object($obj)) {
                return self::getObject($obj, $values, $curValueIndex + 1);
            } else {
                return $obj;
            }
        } catch (\Exception $ex) {
            return '';
        }
    }


    /**
     * @param array $entities
     * @return string
     * @throws InvalidArgumentException
     */
    public function getGridNameFromEntities($entities): string
    {
        /* Get name */
        $first = reset($entities);

        if (!is_object($first)) {
            throw new InvalidArgumentException('Entities must be array of entities (array of objects).');
        }

        $rc = new \ReflectionClass($first);
        return strtolower($rc->getShortName());
    }


    /**
     * @param object|[] $var
     * @return bool
     */
    protected function isIterable($var) : bool
    {
        return (is_array($var) || $var instanceof \Traversable);
    }


    /**
     *
     * @param FilterInterface $FilterInterface
     * @return GridManager
     */
    public function addFilter(FilterInterface $FilterInterface) : GridManager
    {
        if (!empty($FilterInterface->getName())) {
            $this->filter[$FilterInterface->getName()] = $FilterInterface;
        } else {
            $this->filter[] = $FilterInterface;
        }

        return $this;
    }


    /**
     * @param $column
     * @return FilterInterface
     * @throws \BadFunctionCallException
     */
    public function getFilter($column) : FilterInterface
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
            function (FilterInterface $v) {
                return $v->isGlobal();
            }
        );
    }
}
