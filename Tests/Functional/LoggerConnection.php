<?php

namespace Trinity\Bundle\GridBundle\Tests\Functional;

use Trinity\Bundle\LoggerBundle\Interfaces\LoggerTtlProviderInterface;
use Trinity\Bundle\LoggerBundle\Interfaces\UserProviderInterface;
use Trinity\Component\Core\Interfaces\UserInterface;

class LoggerConnection implements LoggerTtlProviderInterface, UserProviderInterface
{

    /**
     * Get ttl in days for the given type.
     *
     * @param string $typeName Name of the elasticLog type
     *
     * @return int Ttl in days. 0(zero) stands for no ttl.
     */
    public function getTtlForType(string $typeName): int
    {
        return 30;
    }

    /**
     * Get user by id.
     *
     * @param int $userId
     *
     * @return UserInterface
     */
    public function getUserById(int $userId): UserInterface
    {
        return new class implements UserInterface{

            /**
             * Get id.
             */
            public function getId()
            {
                return 0;
            }

            /**
             * @return string
             */
            public function __toString()
            {
                return '';
            }

            /**
             * @return string
             */
            public function getFirstName()
            {
                return '';
            }

            /**
             * @return string
             */
            public function getLastName()
            {
                return '';
            }

            /**
             * @return string
             */
            public function getFullName()
            {
                return '';
            }

            /**
             * @return string
             */
            public function getPhoneNumber()
            {
                return '';
            }

            /**
             * @return string
             */
            public function getWebsite()
            {
                return '';
            }

            /**
             * @return string
             */
            public function getAvatar()
            {
                return '';
            }

            /**
             * @return bool
             */
            public function getPublic()
            {
                return '';
            }

            /**
             * @return int
             */
            public function getSettingIdentifier()
            {
                return '';
            }
        };
    }
}