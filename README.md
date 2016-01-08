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

###3) Parse entity array to array of strings

From container pull service 'trinity.grid.manager'

    $manager = $container->get('trinity.grid.manager');
    
Parse array of entities:
    
    $stringArray = $manager->convertEntitiesToArray(
                $this->getArrayOfEntities(),                                        // array of entities
                ['id', 'name', 'description', 'nonexistentColumn', 'createdAt']     // columns
            );
            
From the entity name is found grid. $stringArray is must be convert to JSON.            