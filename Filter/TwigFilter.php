<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\Filter;

use Trinity\Bundle\GridBundle\Grid\BaseGrid;


/**
 * Class TwigFilter
 * @package Trinity\Bundle\GridBundle\Filter
 */
class TwigFilter extends BaseFilter
{

    /** @var bool */
    protected $global = true;

    /** @var  \Twig_Environment */
    protected $twig;


    /**
     * TwigFilter constructor.
     * @param $twig
     */
    public function __construct($twig)
    {
        $this->twig = $twig;
    }


    /**
     * @param string|object|int|bool $input
     * @param array $arguments
     * @return string
     */
    function process($input, array $arguments = [])
    {

        /** @var BaseGrid $grid */

        $grid      = $arguments['grid'];

        /** @var string  $column */
        $column    = $arguments['column'];

        /** @var object $entity */
        $entity    = $arguments['entity'];

        if(is_array($column)){
            $column = join('_', $column);
        }

        $templates   = [];
        $templates[] = $this->twig->loadTemplate($grid->getLayout());

        foreach ($grid->getTemplates() as $template) {
            $templates[] = $this->twig->loadTemplate($template);
        }

        foreach ($templates as $template) {
            if ($template->hasBlock('cell_'.$column)) {
                $input = trim($template->renderBlock("cell_".$column, ['row' => $entity, 'entity' => $entity, 'value' => $input]));
            }
        }

        return $input;
    }


}