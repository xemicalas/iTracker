<?php

namespace KTU\CountersBundle\Model;

use Doctrine\ORM\EntityManager;

class CounterStatisticsModel
{

    /**
     * Generates full statistics array
     * @param array $stats
     * @param $days
     * @return array
     */
    private static function generateStatsArray(array $stats, $days) {
        $todays = new \DateTime('now');
        $newStats = array();
        $found = false;

        for ($i = 0; $i < $days; $i++) {
            $loopDate = clone $todays;
            $loopDate->sub(new \DateInterval('P'.$i.'D'));
            foreach ($stats as $stat) {
                if ($stat['date']->format('Y-m-d') == $loopDate->format('Y-m-d')) {
                    $newStats[] = $stat;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $newStats[] = array(
                    'date' => $loopDate,
                    'total' => 0,
                    'uniq' => 0
                );
            }
            $found = false;
        }
        return $newStats;
    }

    /**
     * Gets unique and total numbers of visitors
     * @param EntityManager $manager
     * @return mixed
     */
    public static function getVisitorsStatistics(EntityManager $manager)
    {
        $query = $manager->createQueryBuilder()
            ->select('COUNT(stats.ip) AS total, COUNT(DISTINCT stats.ip) AS uniq')
            ->from('KTUCountersBundle:CounterStatistics', 'stats')
            ->setMaxResults(1)
            ->getQuery();
        $visitors = $query->getOneOrNullResult();

        return $visitors;
    }

    /**
     * Gets unique and total numbers of visitors by given date
     * @param EntityManager $manager
     * @param $date
     * @return array
     */
    public static function getVisitorsStatisticsByDate(EntityManager $manager, $date)
    {
        $query = $manager->createQueryBuilder()
            ->select('COUNT(stats.ip) AS total, COUNT(DISTINCT stats.ip) AS uniq')
            ->from('KTUCountersBundle:CounterStatistics', 'stats')
            ->where('stats.date = :date')
            ->setParameter('date', $date)
            ->setMaxResults(1)
            ->getQuery();
        $visitors = $query->getOneOrNullResult();

        return $visitors;
    }

    /**
     * Gets counters statistics by last $days days
     * @param EntityManager $manager
     * @param $counterId
     * @param $days
     * @return array
     */
    public static function getLastStatsByCountersId(EntityManager $manager, $counterId, $days)
    {
        $query = $manager->createQueryBuilder()
            ->select('stats.date, COUNT(stats.ip) AS total, COUNT(DISTINCT stats.ip) AS uniq')
            ->from('KTUCountersBundle:CounterStatistics', 'stats')
            ->where('stats.counter_id = :id AND (stats.date BETWEEN DATE_ADD(CURRENT_DATE(), :interval, \'DAY\') AND CURRENT_DATE())')
            ->groupBy('stats.date')
            ->orderBy('stats.date', 'DESC')
            ->setParameters(array('id' => $counterId, 'interval' => ($days + 1)))
            ->getQuery();
        $stats = $query->getArrayResult();
        $stats = self::generateStatsArray($stats, abs($days));

        return $stats;
    }

    /**
     * Gets total unique visitors and total hits by a particular counters ID
     * @param EntityManager $manager
     * @param $counterId
     * @return array
     */
    public static function getTotalStatsByCountersId(EntityManager $manager, $counterId)
    {
        $query = $manager->createQueryBuilder()
            ->select('COUNT(stats.ip) AS total, COUNT(DISTINCT stats.ip) AS uniq')
            ->from('KTUCountersBundle:CounterStatistics', 'stats')
            ->where('stats.counter_id = :id')
            ->setParameter('id', $counterId)
            ->setMaxResults(1)
            ->getQuery();
        $totals = $query->getOneOrNullResult();

        return $totals;
    }

    /**
     * Gets counters statistics (total hits, unique visitors) by particular date.
     * @param EntityManager $manager
     * @param $counterId
     * @param $date
     * @return mixed
     */
    public static function getCountersVisitorsStatisticsByDate(EntityManager $manager, $counterId, $date)
    {
        $query = $manager->createQueryBuilder()
            ->select('COUNT(stats.ip) AS total, COUNT(DISTINCT stats.ip) AS uniq')
            ->from('KTUCountersBundle:CounterStatistics', 'stats')
            ->where('stats.date = :date AND stats.counter_id = :counter')
            ->setParameters(array('date' => $date, 'counter' => $counterId))
            ->setMaxResults(1)
            ->getQuery();
        $visitors = $query->getOneOrNullResult();

        return $visitors;
    }

    /**
     * Gets all counter statistics by given counter ID.
     * @param EntityManager $manager
     * @param $counterId
     * @return array
     */
    public static function getStatsByCounterId(EntityManager $manager, $counterId)
    {
        $query = $manager->createQueryBuilder()
            ->select('stats')
            ->from('KTUCountersBundle:CounterStatistics', 'stats')
            ->where('stats.counter_id = :id')
            ->setParameter('id', $counterId)
            ->getQuery();
        $stats = $query->getResult();

        return $stats;
    }
}