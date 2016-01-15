<?php

namespace Trinity\Bundle\GridBundle\Tests\Functional\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;


/**
 * Class Group
 * @package Trinity\Bundle\GridBundle\Tests\Functional\Entity
 *
 * @ORM\Table()
 * @ORM\Entity()
 *
 */
class UserGroup
{

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;


    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="groups")
     */
    private $users;



    public function __construct() {
        $this->users = new ArrayCollection();
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }


    /**
     * @param mixed $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


    /**
     * @param User $user
     * @return $this
     */
    public function addUser(User $user){
        $this->users->add($user);

        return $this;
    }

}