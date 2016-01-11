#Trinity Grid

Trinity grid is part of Trinity package. 

Description:

* Converts an array of entities to array of strings.
* Array of string is convert to JSON:


##Documentation

###1) Create Grid

Create class extended by BaseGrid. Add templates for change attributes value.

    class ProductGrid extends BaseGrid
    {
        public function setUp()
        {
            $this->addTemplate("ProductGrid.html.twig");
        }
    }
    
    
####Set up
    addTemplate     - add new template for changing values. 
    setColumnFormat - for basic chage value of date or simple text edit.
    
Template:
    
cell_attributeName for change value. 

    {% block cell_name %} Template edit - {{ value }} {% endblock %}
    
Available variables are: 
    - value 
    - row - entity object
    
    
###2) Register grid  

File: services.yml 
  
    services:
      trinity.grid.test.product:
        class: Trinity\Bundle\GridBundle\Tests\Functional\Grid\ProductGrid
        tags:
            - { name: trinity.grid, alias: product }
            
Tag name: trinity.grid  
Alias is mandatory value for searching grids from entity name. 

Product entity has product alias. 

####Grid filters

Filters are used to change the attribute values.

#####Special grid:

Special filters are used for changing specific attribute (id, name, etc.).

1) Create filter:

Filter -> BaseFilter or GridFilterInterface!

Attribute 'name' is necessary!



    class IdFilter extends BaseFilter
    {
    
        /**
         * @var string
         */
        protected $name = 'id';
    
    
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
    
2) Grid registration:

File: service.yml
Tag: trinity.grid.filter
    
   
  trinity.grid.filter.id:
      class: Trinity\Bundle\GridBundle\Filter\IdFilter
      tags:
        - {name: "trinity.grid.filter"}
    
3) Set up filter for current grid:
    
     $this->setColumnFilter('id', 'id');
     
First attribute 'id' -> column name.

Second attribute -> filter name.     

#####Global filters

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

###3) Parse entity array to array of strings

From container pull service 'trinity.grid.manager'

    $manager = $container->get('trinity.grid.manager');
    
Parse array of entities:
    
    $stringArray = $manager->convertEntitiesToArray(
                $this->getArrayOfEntities(),                                        // array of entities
                ['id', 'name', 'description', 'nonexistentColumn', 'createdAt']     // columns
            );
            
From the entity name is found grid. $stringArray is must be convert to JSON.            