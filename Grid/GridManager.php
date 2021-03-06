<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\Grid;

use Doctrine\ORM\PersistentCollection;
use Trinity\Bundle\GridBundle\Exception\GridNotFoundException;
use Trinity\Bundle\GridBundle\Exception\InvalidArgumentException;
use Trinity\Bundle\GridBundle\Filter\FilterInterface;
use Trinity\Bundle\SearchBundle\NQL\Column;
use Trinity\Bundle\SearchBundle\Utils\StringUtils;
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
        $this->grids[strtolower($alias)] = $grid;

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
     * Get registered grid
     * @param String|boolean $name
     * @return BaseGrid
     * @throws \Trinity\Bundle\GridBundle\Exception\GridNotFoundException
     */
    public function getGrid(string $name): BaseGrid
    {
        $name = \strtolower($name);

        $lastSlashIndex = strrpos($name, '\\');

        if ($lastSlashIndex && StringUtils::endsWith($name, 'grid')) {
            $name = \substr($name, $lastSlashIndex + 1, -\strlen('grid'));
        }

        if (\array_key_exists($name, $this->grids)) {
            return $this->grids[$name];
        }

        throw new GridNotFoundException("Grid $name does not exist or has not been registered yet.");
    }

    /**
     * @param array|\Iterator $entities
     * @param array $columns
     * @param string $gridName
     *
     * @return array
     * @throws \ReflectionException
     * @throws \Trinity\Bundle\GridBundle\Exception\GridNotFoundException
     * @throws \BadFunctionCallException
     * @throws \Trinity\Bundle\SearchBundle\Exception\SyntaxErrorException
     * @throws InvalidArgumentException
     * @throws MemberAccessException
     */
    public function convertEntitiesToArray($entities, $columns, ?string $gridName = null): array
    {
        if (!$this->isIterable($entities)) {
            throw new InvalidArgumentException('Argument \'entities\' is not iterable.');
        }

        if (\count($entities) > 0) {
            $grid = $this->getGrid(
                $gridName ?? $this->getGridNameFromEntities($entities)
            );
        }

        $arrayResult = [];

        foreach ($entities as $entity) {
            /** @noinspection PhpUndefinedVariableInspection */
            $arrayResult[] = $this->select($columns, $entity, $grid);
        }

        return $arrayResult;
    }

    /**
     * @param array $columns
     * @param $entity
     * @param BaseGrid $grid
     *
     * @return array
     * @throws \ReflectionException
     * @throws \BadFunctionCallException
     * @throws \Trinity\Bundle\SearchBundle\Exception\SyntaxErrorException
     */
    private function select(array $columns, $entity, BaseGrid $grid) : array
    {
        $row = [];
        foreach ($columns as $column) {
            $value = $this->getValue($entity, $column, $grid);

            $col = Column::parse(\str_replace('.', ':', $column));

            $key = \count($col->getJoinWith()) ? $col->getJoinWith()[0] : $col->getName();

            if (\array_key_exists($key, $row)) {
                if (\is_array($value) && \is_array($row[$key])) {
                    /** @noinspection SlowArrayOperationsInLoopInspection */
                    $row[$key] = \array_replace_recursive($row[$key], $value);
                }
            } else {
                $row[$key] = $value;
            }
        }

        return $row;
    }

    /**
     * @param object $entity
     * @param string $value
     * @param BaseGrid $grid
     *
     * @return mixed|string
     * @throws \ReflectionException
     * @throws \BadFunctionCallException
     */
    public function getValue($entity, $value, BaseGrid $grid)
    {
        $values = \explode('.', $value);
        return $this->getObject($entity, $values, 0, $grid);
    }

    /**
     * @param object $entity
     * @param array $values
     * @param int $curValueIndex
     * @param BaseGrid $grid
     *
     * @return mixed|string
     * @throws \ReflectionException
     * @throws \BadFunctionCallException
     */
    private function getObject($entity, $values, $curValueIndex, BaseGrid $grid)
    {
        try {
            $obj = ObjectMixin::get($entity, $values[$curValueIndex]);
        } catch (MemberAccessException $ex) {
            $obj = '';
        }

        $currentColumnPart = \implode('.', \array_slice($values, 0, $curValueIndex + 1));
        $filter = $grid->getColumnFormat($currentColumnPart);

        $filteredObj = $obj;

        if (!empty($filter)) {
            $filter = $this->getFilter($filter);
            $filteredObj = $filter->process(
                $obj,
                ['column' => \str_replace('.', '_', $currentColumnPart), 'entity' => $entity, 'grid' => $grid]
            );
        }

        /** @var FilterInterface[] $filters */
        $filters = $this->getGlobalFilters();

        if ($filteredObj === $obj) {
            foreach ($filters as $filter) {
                $filteredObj = $filter->process(
                    $obj,
                    ['column' => \str_replace('.', '_', $currentColumnPart), 'entity' => $entity, 'grid' => $grid]
                );
            }
        }

        if ($filteredObj !== $obj) {
            return $curValueIndex ? [$values[$curValueIndex] => $filteredObj] : $filteredObj;
        }
        // else
        $obj = $filteredObj;

        if ($curValueIndex === count($values) - 1) {
            return $curValueIndex ? [$values[$curValueIndex] => $obj] : $obj;
        }

        if ($obj instanceof PersistentCollection) {
            $items = [];
            foreach ($obj as $item) {
                if ($curValueIndex === 0) {
                    $items[] = $this->getObject($item, $values, $curValueIndex + 1, $grid);
                } else {
                    $items[$values[$curValueIndex]][] = $this->getObject($item, $values, $curValueIndex + 1, $grid);
                }
            }
            return $items;
        }

        if (\is_object($obj)) {
            if ($curValueIndex === 0) {
                return $this->getObject($obj, $values, $curValueIndex + 1, $grid);
            }
            // else
            return [$values[$curValueIndex] => $this->getObject($obj, $values, $curValueIndex + 1, $grid)];
        }

        // else
        if ($curValueIndex === 0) {
            return $this->getObject($obj, $values, $curValueIndex + 1, $grid);
        }
        // else
        return [$values[$curValueIndex] => $this->getObject($obj, $values, $curValueIndex + 1, $grid)];
    }


    /**
     * @param array $entities
     * @return string
     * @throws \ReflectionException
     * @throws InvalidArgumentException
     */
    public function getGridNameFromEntities($entities): string
    {
        /* Get name */
        $first = \reset($entities);

        if (!\is_object($first)) {
            throw new InvalidArgumentException('Entities must be array of entities (array of objects).');
        }

        $rc = new \ReflectionClass($first);
        return \strtolower($rc->getShortName());
    }


    /**
     * @param object|[] $var
     * @return bool
     */
    protected function isIterable($var) : bool
    {
        return (\is_array($var) || $var instanceof \Traversable);
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
        if (\array_key_exists($column, $this->filter)) {
            return $this->filter[$column];
        }

        throw new \BadFunctionCallException("Filter '$column' does not exists.");
    }


    /**
     * @return array
     */
    public function getGlobalFilters(): array
    {
        return \array_filter(
            $this->filter,
            function (FilterInterface $v) {
                return $v->isGlobal();
            }
        );
    }
}
