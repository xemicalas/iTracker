<?php

namespace KTU\CountersBundle\Controller;

use KTU\CountersBundle\Components\Locale;
use KTU\CountersBundle\Components\Pagination;
use KTU\CountersBundle\Entity\Categories;
use KTU\CountersBundle\Model\CategoriesModel;
use KTU\CountersBundle\Model\CountersModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CategoryController, categories controller which renders categories content.
 * @package KTU\CountersBundle\Controller
 */
class CategoryController extends Controller
{
    /**
     * Renders counters records by given category
     * @param Request $request
     * @param Categories $category
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showCategoryAction(Request $request, Categories $category, $page)
    {
        $manager = $this->getDoctrine()->getManager();
        $recordsInPage = $this->container->getParameter('ktu_counters.page_size');
        $recordsPage = Pagination::getRecordPagination($page, $recordsInPage);

        $locale = $request->getLocale();
        $locator = new Locale($request);
        $columnName = $locator->getCategoryColumn($locale);

        $category = CategoriesModel::getCategoryById($manager, $category->getId(), $columnName);
        $counters = CountersModel::getCountersPageByCategory($manager, $category['id'], $recordsInPage, $recordsPage);
        $records = CountersModel::getNumberOfCountersByCategory($manager, $category['id']);

        $pages = Pagination::getPages($records['amount'], $recordsInPage);
        $paging = new Pagination(
            $page,
            $records['amount'],
            $recordsInPage,
            $this->generateUrl('ktu_counters_show_category', array('id' => $category['id'], 'page' => 0)));
        $paging->generatePagination();

        return $this->render('KTUCountersBundle:Category:viewCategory.html.twig', array(
            'cat' => $category,
            'counters' => $counters,
            'page' => $page,
            'pagination_section' => $paging,
            'pages' => $pages
        ));
    }
}