<?php

namespace KTU\CountersBundle\Kernel;

use Doctrine\ORM\EntityManager;
use KTU\CountersBundle\Components\Locale;
use KTU\CountersBundle\Model\CategoriesModel;
use KTU\CountersBundle\Model\CountersModel;
use KTU\CountersBundle\Model\CounterStatisticsModel;
use KTU\CountersBundle\Model\UsersModel;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Handler provides functionality when it needs to be executed before every controller
 * Loads global variables to Twig.
 * @package KTU\CountersBundle\Kernel
 */
class Handler extends ContainerAware
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * Doctrine manager'is
     * @var EntityManager
     */
    private $manager;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * Twig object
     * @var object
     */
    private $twig;

    /**
     * @param ContainerInterface $container
     * @param Request $request
     */
    public function __construct(ContainerInterface $container, Request $request)
    {
        $this->container = $container;
        $this->request = $request;
        $this->manager = $this->container->get('doctrine')->getManager();
        $this->twig = $this->container->get('twig');
    }

    /**
     * Sets current locale according to cookies data, if cookies are empty, gets the user's GEO locale
     */
    public function setLocale()
    {
        $locales = $this->container->getParameter('ktu_counters.languages');
        $locator = new Locale($this->request);
        $locator->setGlobLocale($locales);
    }

    /**
     * Loads global variables to Twig
     */
    public function setGlobals()
    {
        $projectName = $this->container->getParameter('project_name');
        $copyrightYear = $this->container->getParameter('copyright_year');
        $pages = $this->container->getParameter('ktu_counters.page_size');
        $image_url = $this->container->getParameter('ktu_counters.portable_image_url');

        $stats = CounterStatisticsModel::getVisitorsStatistics($this->manager);
        $statsToday = CounterStatisticsModel::getVisitorsStatisticsByDate($this->manager, date('Y-m-d'));
        $countersTotal = CountersModel::getTotalNumberOfCounters($this->manager);
        $usersTotal = UsersModel::getTotalNumberOfUsers($this->manager);

        $this->twig->addGlobal('project_name', $projectName);
        $this->twig->addGlobal('copyright_year', $copyrightYear);
        $this->twig->addGlobal('page_size', $pages);
        $this->twig->addGlobal('portable_image_url', $image_url);
        $this->twig->addGlobal('counter_stats_today', $statsToday);
        $this->twig->addGlobal('counter_stats', $stats);
        $this->twig->addGlobal('counters_total', $countersTotal['total']);
        $this->twig->addGlobal('users_total', $usersTotal['total']);
    }

    /**
     * Gets category date and writes to twig globally
     */
    public function renderCategories()
    {
        $locale = $this->request->getLocale();
        $locator = new Locale($this->request);
        $columnName = $locator->getCategoryColumn($locale);

        $categories = CategoriesModel::getCategoriesInfo($this->manager, $columnName);

        $this->twig->addGlobal('categories', $categories);
    }

    /**
     * Executes before a controller was loaded
     */
    public function preRender()
    {
        $this->setGlobals();
        $this->renderCategories();
    }
}