#Trinity Grid

Trinity grid is part of Trinity package. 

Description:

* Converts an array of entities to array of strings.
* Array of string is convert to JSON:


##Documentation

###Grid Template

####1) Create Grid Template

Create class extended by BaseGrid. Add templates for change attributes value.

    class ProductGrid extends BaseGrid
    {
        public function setUp()
        {
            $this->addTemplate("ProductGrid.html.twig"); // add new twig template for changing values.
        }
    }
    
    
#####Set up
    addTemplate     - add new template for changing values. 
    setColumnFormat - for basic change value of date or simple text edit. @todo Jancar example (dog eyes, nicely please)
    
#####Template:
    
For change value of cell use block cell_attributeName.

    {% block cell_name %} Template edit - {{ value }} {% endblock %}
    
<b>Available variables:</b>
- value = value of cell
- row = entity object
    
    
####2) Register Grid Template

In services.yml

    services:
      trinity.grid.test.product:
        class: Trinity\Bundle\GridBundle\Tests\Functional\Grid\ProductGrid
        tags:
            - { name: trinity.grid, alias: product } // tag name have to be trinity.grid, alias is mandatory value for searching grids from entity name.


###Grid filters

Filters are used to change the attribute values.

####1) Create filter:

Filter -> BaseFilter or FilterInterface!

    class IdFilter extends BaseFilter
    {
        /**
         * @var string
         */
        protected $name = 'id';  //mandatory for not global filter

        /**
         * @param string|object|int|bool $input
         * @param array $arguments
         * @return string
         */
        function process($input, array $arguments = []) : string
        {
            return $input.'.';
        }
    }
    
####2) Grid registration:

In services.yml

  trinity.grid.filter.id:
      class: Trinity\Bundle\GridBundle\Filter\IdFilter
      tags:
        - {name: "trinity.grid.filter"} //tag name have to be trinity.grid.filter
    
####3) Set up filter for current grid:
    
     $this->setColumnFilter('columnName', 'filterName');


####4) Global filters

For global filter must be set attribute 'global' to TRUE;

Attribute 'name' is not necessary.


    class ObjectFilter extends BaseFilter
    {
        protected $global = true;
     
        /**
         * @param string|object|int|bool $input
         * @param array $arguments
         * @return string
         */
        function process($input, array $arguments = []) : string
        {
            if ((is_object($input) && method_exists($input, 'getName'))) {
                $input = $input->getName();
            } elseif (is_object($input)) {
                $input = (string)$input;
            }
            return $input;
        }
    }

###Parse entity array to array of strings

From container pull service 'trinity.grid.manager'

    $manager = $container->get('trinity.grid.manager');
    
Parse array of entities:
    
    $stringArray = $manager->convertEntitiesToArray(
                $this->getArrayOfEntities(),                                        // array of entities
                ['id', 'name', 'description', 'nonexistentColumn', 'createdAt']     // columns
            );
            
From the entity name is found grid. $stringArray is must be convert to JSON.            