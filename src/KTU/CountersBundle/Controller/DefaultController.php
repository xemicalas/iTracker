<?php

namespace KTU\CountersBundle\Controller;

use KTU\CountersBundle\Components\Locale;
use KTU\CountersBundle\Model\CategoriesModel;
use KTU\CountersBundle\Model\CountersModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController. Homepage kontroleris.
 * @package KTU\CountersBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * Atvaizduojamas pagrindinis puslapis. Jame atvaizduojami yra TOP skaitliukai ir visos kategorijos.
     * @return Response
     */
    public function indexAction()
    {
        $manager = $this->getDoctrine()->getManager();
        $topPages = $this->container->getParameter('ktu_counters.top_pages');
        // Gaunami pirmi top $topPages skaitliukai
        $counters = CountersModel::getTopCounters($manager, $topPages, date('Y-m-d'));
        // Gaunamos visos kategorijos
        $categories = CategoriesModel::getCategoriesInfo($manager);

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
