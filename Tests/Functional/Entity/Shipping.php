<?php


namespace Trinity\Bundle\GridBundle\Tests\Functional\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;


/**
 * Class Shipping
 * @package Trinity\Bundle\GridBundle\Tests\Functional\Entity
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Shipping
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
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $price;


    /**
     * @ORM\OneToOne(targetEntity="Product", mappedBy="shipping")
     */
    private $product;


    /**w
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }


    /**
     * @param int $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }


    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }


    /**
     * @param mixed $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

}