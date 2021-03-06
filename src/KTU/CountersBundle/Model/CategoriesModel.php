<?php

namespace KTU\CountersBundle\Model;

use Doctrine\ORM\EntityManager;

class CategoriesModel
{

    /**
     * Gets all categories and counts how many counters there are in each category
     * @param EntityManager $manager
     * @param $categoryColumn Categories column name
     * @return array
     */
    public static function getCategoriesInfo(EntityManager $manager, $categoryColumn)
    {
        $query = $manager->createQueryBuilder()
            ->select('categories.id, categories.' . $categoryColumn . ' AS category, COUNT(counters.id) AS counters_amount')
            ->from('KTUCountersBundle:Categories', 'categories')
            ->leftJoin('categories.counters', 'counters')
            ->groupBy('categories.id')
            ->orderBy('categories.id', 'ASC')
            ->getQuery();
        $categories = $query->getResult();
        return $categories;
    }

    /**
     * Gets all categories
     * @param EntityManager $manager
     * @return array
     */
    public static function getCategories(EntityManager $manager)
    {
        $query = $manager->createQueryBuilder()
            ->select('categories')
            ->from('KTUCountersBundle:Categories', 'categories')
            ->getQuery();
        $categories = $query->getResult();
        return $categories;
    }

    /**
     * Gets particular category with it's translated name by ID
     * @param EntityManager $manager
     * @param $categoryId
     * @param string $categoryColumn Category's column name
     * @return mixed
     */
    public static function getCategoryById(EntityManager $manager, $categoryId, $categoryColumn = 'category')
    {
        $query = $manager->createQueryBuilder()
            ->select('categories.id, categories.' . $categoryColumn . ' AS category')
            ->from('KTUCountersBundle:Categories', 'categories')
            ->where('categories.id = :id')
            ->setParameter('id', $categoryId)
            ->setMaxResults(1)
            ->getQuery();
        $category = $query->getOneOrNullResult();
        return $category;
    }

    /**
     * Formats categories to choice list
     * @param array $categories Categories array
     * @param $locale
     * @return array
     */
    public static function formatToChoiceList(array $categories, $locale)
    {
        foreach ($categories as &$category) {
            $category->setLocale($locale);
        }
        return $categories;
    }

    /**
     * Gets all category's columns by given ID
     * @param EntityManager $manager
     * @param $categoryId
     * @return mixed
     */
    public static function getFullCategoryById(EntityManager $manager, $categoryId)
    {
        $query = $manager->createQueryBuilder()
            ->select('categories')
            ->from('KTUCountersBundle:Categories', 'categories')
            ->where('categories.id = :id')
            ->setParameter('id', $categoryId)
            ->setMaxResults(1)
            ->getQuery();
        $category = $query->getOneOrNullResult();
        return $category;
    }
}