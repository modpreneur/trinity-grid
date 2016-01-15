<?php

namespace Trinity\Bundle\GridBundle\Tests\Functional\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;


/**
 * Class Address
 * @package Trinity\Bundle\GridBundle\Tests\Functional\Entity
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Address
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $city;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $streetNumber;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }


    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }


    /**
     * @return string
     */
    public function getStreetNumber()
    {
        return $this->streetNumber;
    }


    /**
     * @param string $streetNumber
     */
    public function setStreetNumber($streetNumber)
    {
        $this->streetNumber = $streetNumber;
    }

}