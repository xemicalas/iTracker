<?php

namespace KTU\CountersBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;

class CountersModel
{

    /**
     * Gets today's first $recordsInPage counters from database shifted by $recordsPage (LIMIT $recordsPage, $recordsInPage) by
     * a particular category
     * @param EntityManager $manager
     * @param $categoryId
     * @param $recordsInPage
     * @param $recordsPage
     * @return array
     */
    public static function getCountersPageByCategory(EntityManager $manager, $categoryId, $recordsInPage, $recordsPage)
    {
        $query = $manager->createQueryBuilder()
            ->select('counters.id, counters.name, counters.url,
            (SELECT COUNT(DISTINCT stats.ip) FROM KTUCountersBundle:CounterStatistics stats
            WHERE stats.counter_id = counters.id AND stats.date = :date) AS counter_uniq')
            ->from('KTUCountersBundle:Counters', 'counters')
            ->where('counters.cat = :category')
            ->groupBy('counters.name')
            ->orderBy('counter_uniq', 'DESC')
            ->setFirstResult($recordsPage)
            ->setMaxResults($recordsInPage)
            ->setParameters(array('category' => $categoryId, 'date' => date('Y-m-d')))
            ->getQuery();

        $counters = $query->getArrayResult();
        foreach ($counters as &$counter) {
            $query = $manager->createQueryBuilder()
                ->select('COUNT(stats) AS counter_all')
                ->from('KTUCountersBundle:CounterStatistics', 'stats')
                ->where('stats.counter_id = :id AND stats.date = :date')
                ->setParameters(array('id' => $counter['id'], 'date' => date('Y-m-d')))
                ->setMaxResults(1)
                ->getQuery();
            $res = $query->getOneOrNullResult();
            $counter['counter_all'] = $res['counter_all'];
        }
        return $counters;
    }

    /**
     * Gets all counters by category ID
     * @param EntityManager $manager
     * @param $categoryId
     * @return array
     */
    public static function getCountersByCategory(EntityManager $manager, $categoryId) {
        $query = $manager->createQueryBuilder()
            ->select('counters')
            ->from('KTUCountersBundle:Counters', 'counters')
            ->where('counters.category = :id')
            ->setParameter('id', $categoryId)
            ->getQuery();

        $counters = $query->getResult();

        return $counters;
    }

    /**
     * Gets number of counters in particular category
     * @param EntityManager $manager
     * @param $categoryId
     * @return mixed
     */
    public static function getNumberOfCountersByCategory(EntityManager $manager, $categoryId)
    {
        $query = $manager->createQueryBuilder()
            ->select('COUNT(counters) AS amount')
            ->from('KTUCountersBundle:Counters', 'counters')
            ->where('counters.cat = :category')
            ->setParameter('category', $categoryId)
            ->getQuery();
        $data = $query->getOneOrNullResult();

        return $data;
    }

    /**
     * Gets total number of counters
     * @param EntityManager $manager
     * @return mixed
     */
    public static function getTotalNumberOfCounters(EntityManager $manager)
    {
        $query = $manager->createQueryBuilder()
            ->select('COUNT(counters.id) AS total')
            ->from('KTUCountersBundle:Counters', 'counters')
            ->setMaxResults(1)
            ->getQuery();
        $total = $query->getOneOrNullResult();

        return $total;
    }

    /**
     * Gets first $topRecords top counters in particular day
     * @param EntityManager $manager
     * @param $topRecords
     * @param $day
     * @return array
     */
    public static function getTopCounters(EntityManager $manager, $topRecords, $day)
    {
        $query = $manager->createQueryBuilder()
            ->select('counters.id, counters.name, counters.url,
            (SELECT COUNT(DISTINCT stats.ip) FROM KTUCountersBundle:CounterStatistics stats
            WHERE stats.counter_id = counters.id AND stats.date = :date) AS counter_uniq')
            ->from('KTUCountersBundle:Counters', 'counters')
            ->groupBy('counters.name')
            ->orderBy('counter_uniq', 'DESC')
            ->setMaxResults($topRecords)
            ->setParameter('date', $day)
            ->getQuery();

        $counters = $query->getArrayResult();
        foreach ($counters as &$counter) {
            $query = $manager->createQueryBuilder()
                ->select('COUNT(stats) AS counter_all')
                ->from('KTUCountersBundle:CounterStatistics', 'stats')
                ->where('stats.counter_id = :id AND stats.date = :date')
                ->setParameters(array('id' => $counter['id'], 'date' => $day))
                ->setMaxResults(1)
                ->getQuery();
            $res = $query->getOneOrNullResult();
            $counter['counter_all'] = $res['counter_all'];
        }

        return $counters;
    }

    /**
     * Get counter by particular counter's ID
     * @param EntityManager $manager
     * @param $counterId
     * @return mixed
     */
    public static function getCounterById(EntityManager $manager, $counterId)
    {
        $query = $manager->createQueryBuilder()
            ->select('counters')
            ->from('KTUCountersBundle:Counters', 'counters')
            ->where('counters.id = :id')
            ->setParameter('id', $counterId)
            ->getQuery();
        $counter = $query->getOneOrNullResult();

        return $counter;
    }

    /**
     * Gets counters by given user ID
     * @param EntityManager $manager
     * @param $userId
     * @return array
     */
    public static function getCountersByUserId(EntityManager $manager, $userId)
    {
        $query = $manager->createQueryBuilder()
            ->select('counters')
            ->from('KTUCountersBundle:Counters', 'counters')
            ->where('counters.user_id = :user')
            ->setParameter('user', $userId)
            ->orderBy('counters.id', 'ASC')->getQuery();
        $counters = $query->getResult();

        return $counters;
    }

    /**
     * Gets counters and each counter's statistics by a particular users ID.
     * @param EntityManager $manager
     * @param $userId
     * @return array
     */
    public static function getCountersAndStatsByUser(EntityManager $manager, $userId)
    {
        $counters = CountersModel::getCountersByUserId($manager, $userId);

        foreach ($counters as $counter) {
            $stats = CounterStatisticsModel::getLastStatsByCountersId($manager, $counter->getId(), -5);
            $counter->setStatistics(new ArrayCollection($stats));
        }

        return $counters;
    }

    /**
     * Gets the latest counter by given user ID.
     * @param EntityManager $manager
     * @param $userId
     * @return mixed
     */
    public static function getLatestCounterByUser(EntityManager $manager, $userId)
    {
        $query = $manager->createQueryBuilder()
            ->select('counters')
            ->from('KTUCountersBundle:Counters', 'counters')
            ->where('counters.user_id = :id')
            ->orderBy('counters.id', 'DESC')
            ->setMaxResults(1)
            ->setParameter('id', $userId)
            ->getQuery();
        $counter = $query->getOneOrNullResult();

        return $counter;
    }

    /**
     * Gets counter by counter's URL.
     * @param EntityManager $manager
     * @param $url
     * @return mixed
     */
    public static function getCounterByUrl(EntityManager $manager, $url)
    {
        $query = $manager->createQueryBuilder()
            ->select('counters')
            ->from('KTUCountersBundle:Counters', 'counters')
            ->where('counters.url = :url')
            ->setParameter('url', $url)
            ->setMaxResults(1)
            ->getQuery();

        $counter = $query->getOneOrNullResult();

        return $counter;
    }
}