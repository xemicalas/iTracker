<?php

namespace KTU\CountersBundle\Kernel;

use Doctrine\ORM\EntityManager;
use KTU\CountersBundle\Model\CategoriesModel;
use KTU\CountersBundle\Model\CountersModel;
use KTU\CountersBundle\Model\CounterStatisticsModel;
use KTU\CountersBundle\Model\UsersModel;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Handler. Handler'is, kuris yra vykdomas prieš kiekvieną kontrolerio vykdymą.
 * Užkrauna globalius kintamuosius į Twig. Atvaizduoja detales būdingas kiekvienam puslapiui.
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
     * Twig objektas
     * @var object
     */
    private $twig;

    public function __construct(ContainerInterface $container, Request $request)
    {
        $this->container = $container;
        $this->request = $request;
        $this->manager = $this->container->get('doctrine')->getManager();
        $this->twig = $this->container->get('twig');
    }

    /**
     * Nustato dabartinę kalbą pagal cookie esančią locale reikšmę.
     */
    public function setLocale()
    {
        if (($locale = $this->request->cookies->get('locale')) != null) {
            $this->request->setLocale($locale);
        }
    }

    /**
     * Užkrauna globalius kintamuosius į twig
     */
    public function setGlobals()
    {
        $pages = $this->container->getParameter('ktu_counters.page_size');
        $image_url = $this->container->getParameter('ktu_counters.portable_image_url');

        $stats = CounterStatisticsModel::getVisitorsStatistics($this->manager);
        $statsToday = CounterStatisticsModel::getVisitorsStatisticsByDate($this->manager, date('Y-m-d'));
        $countersTotal = CountersModel::getTotalNumberOfCounters($this->manager);
        $usersTotal = UsersModel::getTotalNumberOfUsers($this->manager);

        $this->twig->addGlobal('page_size', $pages);
        $this->twig->addGlobal('portable_image_url', $image_url);
        $this->twig->addGlobal('counter_stats_today', $statsToday);
        $this->twig->addGlobal('counter_stats', $stats);
        $this->twig->addGlobal('counters_total', $countersTotal['total']);
        $this->twig->addGlobal('users_total', $usersTotal['total']);
    }

    /**
     * Gauna kategorijų duomenis ir įrašo į twig globaliai
     */
    public function renderCategories()
    {
        $categories = CategoriesModel::getCategoriesInfo($this->manager);
        $this->twig->addGlobal('categories', $categories);
    }

    /**
     * Vykdomi veiksmai prieš kontrolerio užkrovimą
     */
    public function preRender()
    {
        $this->setGlobals();
        $this->renderCategories();
    }
}