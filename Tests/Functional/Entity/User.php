<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\Tests\Functional\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;


/**
 * Class User
 * @package Trinity\Bundle\GridBundle\Tests\Functional\Entity
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class User
{

    use ORMBehaviors\Timestampable\Timestampable;

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
    private $firstName;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $lastName;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     */
    private $mail;

    /**
     * @var Address
     *
     * @ORM\ManyToOne(targetEntity="Address")
     */
    private $address;


    /**
     * @var UserGroup[]
     *
     * @ORM\ManyToMany(targetEntity="UserGroup", inversedBy="users")
     * @ORM\JoinTable("user_group")
     */
    private $groups;


    /**
     * User constructor.
     */
    public function __construct() {
        $this->groups = new ArrayCollection();
    }


    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }


    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }


    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }


    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }


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
    public function getFirstName()
    {
        return $this->firstName;
    }


    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }


    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }


    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }


    /**
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }


    /**
     * @param string $mail
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    }


    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }


    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }


    /**
     * @return UserGroup[]
     */
    public function getGroups()
    {
        return $this->groups;
    }


    /**
     * @param UserGroup[] $groups
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;
    }


    /**
     * @param UserGroup $group
     * @return $this
     */
    public function addGroup(UserGroup $group){
        $this->groups->add($group);

        return $this;
    }


}