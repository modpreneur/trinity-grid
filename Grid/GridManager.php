<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\Grid;

use JMS\Serializer\Serializer;
use Trinity\Bundle\GridBundle\Exception\InvalidArgumentException;
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

    /** @var  \Twig_Environment */
    protected $twig;


    /** @var  Serializer */
    protected $serializer;


    /**
     * GridManager constructor.
     * @param $twig
     */
    public function __construct($twig)
    {
        $this->twig = new \Twig_Environment($twig);

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
        if (!$this->is_iterable($entities)) {
            throw new InvalidArgumentException('Agrument \'entities\' is not iterable.');
        }


        $grid = $this->getGrid(
            $this->getGridNameFromEntieies($entities)
        );


        $templates = [];
        $templates[] = $this->twig->loadTemplate($grid->getLayout());

        foreach ($grid->getTemplates() as $template) {
            $templates[] = $this->twig->loadTemplate($template);
        }

        $arrayResult = [];

        foreach ($entities as $entity) {

            $row = [];
            foreach ($columns as $column) {
                $item = "";
                $edited = false;

                try {
                    $item = ObjectMixin::get($entity, $column);
                } catch (MemberAccessException $ex) {

                }

                foreach ($templates as $template) {
                    if ($template->hasBlock('cell_'.$column)) {
                        $item = trim($template->renderBlock("cell_".$column, ['row' => $entity, 'value' => $item]));
                        $edited = true;
                    }
                }

                if(!$edited){
                    if($item instanceof \DateTime){
                        $item = $item->format('m/d/Y H:i');
                    }

                    if(is_object($item) && method_exists($item, 'getName')){
                        $item = $item->getName();
                    }
                }


                $row[$column] = $item;
            }

            $arrayResult[] = $row;
        }

        return $arrayResult;
    }


    /**
     * @param array $entities
     * @return string
     */
    public function getGridNameFromEntieies($entities): string
    {

        /* Get name */
        $first = reset($entities);
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

}