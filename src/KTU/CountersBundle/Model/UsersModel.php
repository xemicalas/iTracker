<?php

namespace KTU\CountersBundle\Model;

use Doctrine\ORM\EntityManager;

class UsersModel
{
    /**
     * Gets total number of registered users
     * @param EntityManager $manager
     * @return mixed
     */
    public static function getTotalNumberOfUsers(EntityManager $manager)
    {
        $query = $manager->createQueryBuilder()
            ->select('COUNT(users.id) AS total')
            ->from('KTUCountersBundle:Users', 'users')
            ->setMaxResults(1)
            ->getQuery();
        $total = $query->getOneOrNullResult();

        return $total;
    }

    /**
     * Gets user by user's ID
     * @param EntityManager $manager
     * @param $userid
     * @return mixed
     */
    public static function getUserById(EntityManager $manager, $userid)
    {
        $query = $manager->createQueryBuilder()
            ->select('users')
            ->from('KTUCountersBundle:Users', 'users')
            ->where('users.id = :id')
            ->setParameter('id', $userid)
            ->setMaxResults(1)
            ->getQuery();

        $user = $query->getOneOrNullResult();

        return $user;
    }
}