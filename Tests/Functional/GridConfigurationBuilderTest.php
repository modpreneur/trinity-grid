<?php
/**
 * Created by PhpStorm.
 * User: fisa
 * Date: 2/10/16
 * Time: 11:50 AM
 */

namespace Necktie\AppBundle\Tests;

use Trinity\Bundle\GridBundle\Grid\GridConfigurationBuilder;
use Symfony\Component\Security\Acl\Exception\Exception;


class GridConfigurationBuilderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Constants
     */
    public $T_URL = 'http://necktie/test';
    public $T_MAX = 10;

    /**
     * @test
     */
    public function createNewBuilder(){

        $expected = array(
            'url' => $this->T_URL,
            'max' => $this->T_MAX,
            'columns'=> array(),
            'editable'=>false,
            'limit' => 15
        );

        $builder = new GridConfigurationBuilder($this->T_URL, $this->T_MAX);
        $this->assertEquals($expected, $builder->getConfiguration(), 'Creating initial configuration failed!');
    }

    /**
     * @test
     * @throws \Trinity\Bundle\GridBundle\Exception\DuplicateColumnException
     */
    public function addColumnSuccess(){
        $c1Name = 'id';
        $c1Label = 'Id';
        $c1Properties = [
            'allowOrder' => false,
            'editable' => false
        ];

        $c2Name = 'name';
        $c2Label = 'Name';
        $c2Properties = [
            'allowOrder' => true,
            'editable' => true
        ];

        $builder = new GridConfigurationBuilder($this->T_URL, $this->T_MAX, 15, true);
        $builder->addColumn($c1Name, $c1Label, $c1Properties);
        $builder->addColumn($c2Name, $c2Label, $c2Properties);

        $expected = array(
            'url' => $this->T_URL,
            'max' => $this->T_MAX,
            'columns'=> array(
                array(
                    'name'=>$c1Name,
                    'label'=>$c1Label,
                    'allowOrder'=>false,
                    'editable'=>false,
                    'hidden'=>false
                ),
                array(
                    'name'=>$c2Name,
                    'label'=>$c2Label,
                    'allowOrder'=>true,
                    'editable'=>true,
                    'hidden'=>false
                )
            ),
            'editable'=>true,
            'limit' => 15
        );

        $this->assertEquals($expected, $builder->getConfiguration(), 'Configuration is different!');

        $builder = new GridConfigurationBuilder($this->T_URL, $this->T_MAX);
        $builder->addColumn($c1Name, $c1Label, $c1Properties);
        $builder->addColumn($c2Name, $c2Label, $c2Properties);

        $expected = array(
            'url' => $this->T_URL,
            'max' => $this->T_MAX,
            'columns'=> array(
                array(
                    'name'=>$c1Name,
                    'label'=>$c1Label,
                    'allowOrder'=>false,
                    'editable'=>false,
                    'hidden'=>false
                ),
                array(
                    'name'=>$c2Name,
                    'label'=>$c2Label,
                    'allowOrder'=>true,
                    'editable'=>false,
                    'hidden'=>false
                )
            ),
            'editable'=>false,
            'limit' => 15
        );
        $this->assertEquals($expected, $builder->getConfiguration(), 'Configuration is different!');
    }

    /**
     * @test
     * @expectedException Exception
     * @throws \Trinity\Bundle\GridBundle\Exception\DuplicateColumnException
     */
    public function addColumnFailed(){
        $c1Name = 'id';
        $c1Label = 'Id';
        $c1Properties = [
            'allowOrder' => false,
            'editable' => false,
            'hidden'=>false
        ];

        $c2Name = 'name';
        $c2Label = 'Name';
        $c2Properties = [
            'allowOrder' => true,
            'editable' => true,
            'hidden'=>false
        ];

        $builder = new GridConfigurationBuilder($this->T_URL, $this->T_MAX);
        $builder->addColumn($c1Name, $c1Label, $c1Properties);
        $builder->addColumn($c2Name, $c2Label, $c2Properties);
        // After this line Exception is raised - column names must be unique
        $builder->addColumn($c2Name, $c2Label, $c2Properties);
    }

    /**
     * @test
     * @throws \Trinity\Bundle\GridBundle\Exception\DuplicateColumnException
     */
    public function removeColumnSuccess(){
        $c1Name = 'id';
        $c1Label = 'Id';
        $c1Properties = [
            'allowOrder' => false,
            'editable' => false
        ];

        $c2Name = 'name';
        $c2Label = 'Name';
        $c2Properties = [
            'allowOrder' => true,
            'editable' => true
        ];

        $builder = new GridConfigurationBuilder($this->T_URL, $this->T_MAX);
        $builder->addColumn($c1Name, $c1Label, $c1Properties);
        $builder->addColumn($c2Name, $c2Label, $c2Properties);

        // Remove ID
        $builder->removeColumn($c1Name);

        $expected = array(
            'url' => $this->T_URL,
            'max' => $this->T_MAX,
            'columns'=> array(
                array(
                    'name'=>$c2Name,
                    'label'=>$c2Label,
                    'allowOrder'=>true,
                    'editable'=>false,
                    'hidden'=>false
                )
            ),
            'editable'=>false,
            'limit' => 15
        );

        $this->assertEquals($expected, $builder->getConfiguration(), 'Configuration is different!');

    }

    /**
     * @test
     * @throws \Trinity\Bundle\GridBundle\Exception\DuplicateColumnException
     */
    public function getJSONSuccess(){
        $c1Name = 'id';
        $c1Label = 'Id';
        $c1Properties = [
            'allowOrder' => false,
            'editable' => false
        ];

        $c2Name = 'name';
        $c2Label = 'Name';
        $c2Properties = [
            'allowOrder' => true,
            'editable' => true
        ];

        $builder = new GridConfigurationBuilder($this->T_URL, $this->T_MAX);
        $builder->addColumn($c1Name, $c1Label, $c1Properties);
        $builder->addColumn($c2Name, $c2Label, $c2Properties);

        $expected = array(
            'url' => $this->T_URL,
            'max' => $this->T_MAX,
            'columns'=> array(
                array(
                    'name'=>$c1Name,
                    'label'=>$c1Label,
                    'allowOrder'=>false,
                    'editable'=>false,
                    'hidden'=>false
                ),
                array(
                    'name'=>$c2Name,
                    'label'=>$c2Label,
                    'allowOrder'=>true,
                    'editable'=>false,
                    'hidden'=>false
                )
            ),
            'editable'=>false,
            'limit' => 15
        );
        $result = $builder->getJSON();
        $expectedStr = json_encode($expected);
        $this->assertJsonStringEqualsJsonString($result, $expectedStr, 'JSON string do not match!');
    }


    /**
     * @test
     * @throws \Trinity\Bundle\GridBundle\Exception\DuplicateColumnException
     */
    public function setPropertyTest(){
        $c1Name = 'id';
        $c1Label = 'Id';
        $c1Properties = [
            'allowOrder' => false,
            'editable' => false
        ];

        $c2Name = 'name';
        $c2Label = 'Name';
        $c2Properties = [
            'allowOrder' => true,
            'editable' => true
        ];


        $builder = new GridConfigurationBuilder($this->T_URL, $this->T_MAX);
        $builder->addColumn($c1Name, $c1Label, $c1Properties);
        $builder->addColumn($c2Name, $c2Label, $c2Properties);
        $builder->setProperty('filter', 'id=2');

        $expected = array(
            'url' => $this->T_URL,
            'max' => $this->T_MAX,
            'columns'=> array(
                array(
                    'name'=>$c1Name,
                    'label'=>$c1Label,
                    'allowOrder'=>false,
                    'editable'=>false,
                    'hidden'=>false
                ),
                array(
                    'name'=>$c2Name,
                    'label'=>$c2Label,
                    'allowOrder'=>true,
                    'editable'=>false,
                    'hidden'=>false
                )
            ),
            'editable'=>false,
            'filter'=>'id=2',
            'limit' => 15
        );
        $this->assertEquals($expected, $builder->getConfiguration(), 'Configuration is different!');
    }

    /**
     * @test
     * @throws \Trinity\Bundle\GridBundle\Exception\DuplicateColumnException
     */
    public function removePropertyTest(){
        $c1Name = 'id';
        $c1Label = 'Id';
        $c1Properties = [
            'allowOrder' => false,
            'editable' => false
        ];

        $c2Name = 'name';
        $c2Label = 'Name';
        $c2Properties = [
            'allowOrder' => true,
            'editable' => true
        ];

        $builder = new GridConfigurationBuilder($this->T_URL, $this->T_MAX);
        $builder->addColumn($c1Name, $c1Label, $c1Properties);
        $builder->addColumn($c2Name, $c2Label, $c2Properties);
        $builder->setProperty('filter', 'id=2');
        $builder->removeProperty('filter');

        $expected = array(
            'url' => $this->T_URL,
            'max' => $this->T_MAX,
            'columns'=> array(
                array(
                    'name'=>$c1Name,
                    'label'=>$c1Label,
                    'allowOrder'=>false,
                    'editable'=>false,
                    'hidden'=>false
                ),
                array(
                    'name'=>$c2Name,
                    'label'=>$c2Label,
                    'allowOrder'=>true,
                    'editable'=>false,
                    'hidden'=>false
                )
            ),
            'editable'=>false,
            'limit' => 15
        );

        $this->assertEquals($expected, $builder->getConfiguration(), 'Configuration is different!');
    }
}