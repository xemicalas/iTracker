<?php

namespace KTU\CountersBundle\Controller;

use KTU\CountersBundle\Components\Locale;
use KTU\CountersBundle\Model\CategoriesModel;
use KTU\CountersBundle\Model\CountersModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController. Homepage controller.
 * @package KTU\CountersBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * Renders main page, includes TOP counters and all categories
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $locale = $request->getLocale();
        $locator = new Locale($request);
        $columnName = $locator->getCategoryColumn($locale);

        $topPages = $this->container->getParameter('ktu_counters.top_pages');
        $counters = CountersModel::getTopCounters($manager, $topPages, date('Y-m-d'));
        $categories = CategoriesModel::getCategoriesInfo($manager, $columnName);

        return $this->render('KTUCountersBundle:Default:index.html.twig', array(
            'counters' => $counters,
            'categories' => $categories
        ));
    }

    /**
     * Changes the user's locale according to the given parameter.
     * @param Request $request
     * @param $locale Locale to be changed.
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function changeLocaleAction(Request $request, $locale)
    {
        $locales = $this->container->getParameter('ktu_counters.languages');
        $locator = new Locale($request);
        if (in_array($locale, $locales)) {
            $locator->setCookieLocale($locale);
        }
        $url = $this->generateUrl('ktu_counters_homepage');
        return $this->redirect($url);
    }
}
