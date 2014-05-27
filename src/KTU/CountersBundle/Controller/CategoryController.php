<?php

namespace KTU\CountersBundle\Controller;

use KTU\CountersBundle\Components\Pagination;
use KTU\CountersBundle\Entity\Categories;
use KTU\CountersBundle\Model\CountersModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CategoryController. Kategorijų kontroleris, kuris atvaizduoja kategorijos turinį.
 * @package KTU\CountersBundle\Controller
 */
class CategoryController extends Controller
{
    /**
     * Atvaizduoja skaitliukų įrašus pagal tam tikrą kategoriją
     * @param Categories $category Categories klasės objektas
     * @param $page int Dabartinis puslapis
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showCategoryAction(Categories $category, $page)
    {
        $manager = $this->getDoctrine()->getManager();
        $recordsInPage = $this->container->getParameter('ktu_counters.page_size');
        $recordsPage = Pagination::getRecordPagination($page, $recordsInPage);

        // Gaunami skaitliukai pagal kategoriją
        $counters = CountersModel::getCountersPageByCategory($manager, $category->getId(), $recordsInPage, $recordsPage);
        // Gaunamas įrašų skaičius pagal kategoriją, reikalingas puslapiavimui
        $records = CountersModel::getNumberOfCountersByCategory($manager, $category->getId());

        // Sukuriamas puslapiavimas
        $pages = Pagination::getPages($records['amount'], $recordsInPage);
        $paging = new Pagination(
            $page,
            $records['amount'],
            $recordsInPage,
            $this->generateUrl('ktu_counters_show_category', array('id' => $category->getId(), 'page' => 0)));
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