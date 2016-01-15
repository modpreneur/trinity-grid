<?php
/**
 * This file is part of Trinity package.
 */

namespace Trinity\Bundle\GridBundle\Tests\Functional;


use Doctrine\ORM\EntityManager;
use Faker\Factory;
use Trinity\Bundle\GridBundle\Tests\Functional\Entity\Address;
use Trinity\Bundle\GridBundle\Tests\Functional\Entity\UserGroup;
use Trinity\Bundle\GridBundle\Tests\Functional\Entity\Product;
use Trinity\Bundle\GridBundle\Tests\Functional\Entity\Shipping;
use Trinity\Bundle\GridBundle\Tests\Functional\Entity\User;


/**
 * Class DataSet
 * @package Trinity\Bundle\GridBundle\Tests\Functional
 */
class DataSet
{

    public function load(EntityManager $entityManager){

        $faker = Factory::create();;

        $groups  = [];
        $addresses = [];

        $a = 10;
        $u = 10;
        $g = 5;
        $p = 10;

        for($i = 0; $i < $g; $i++) {
            $groups[] = $group = new UserGroup();
            $group->setName($faker->name);

            $entityManager->persist($group);
        }


        for($i = 0; $i < $a; $i++) {
            $addresses[] = $address = new Address;
            $address->setCity($faker->city);
            $address->setStreetNumber($faker->numberBetween(1, 100));

            $entityManager->persist($address);
        }
        $entityManager->flush();


        for($i = 0; $i < $u; $i++) {
            $user = new User();

            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);

            $user->setCreatedAt($faker->dateTime);
            $user->setUpdatedAt($faker->dateTime);
            $user->setMail($faker->email);

            $user->setAddress($addresses[$faker->numberBetween(0, 9)]);

            $count = $faker->numberBetween(0, $g);
            for($j = 0; $j < $count; $j++) {
                $groups[$j]->addUser($user);
                $user->addGroup( $groups[$j] );
            }

            $entityManager->persist($user);
        }
        $entityManager->flush();

        for($i = 0; $i < $p; $i++){
            $product = new Product();
            $product->setName($faker->name);
            $entityManager->persist($product);

            $product->setCreatedAt($faker->dateTime);
            $product->setUpdatedAt($faker->dateTime);

            $s = new Shipping();
            $s->setPrice($faker->numberBetween(0, 500));
            $entityManager->persist($s);

            $product->setShipping($s);
            $entityManager->persist($product);
        }

        $entityManager->flush();
    }

}