<?php

namespace KTU\CountersBundle\Controller;

use KTU\CountersBundle\Model\CountersModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class TopsController, renders TOP pages
 * @package KTU\CountersBundle\Controller
 */
class TopsController extends Controller
{
    /**
     * Renders top pages sorted by today's visitors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showTopsAction()
    {
        $manager = $this->getDoctrine()->getManager();
        $counters = CountersModel::getTopCounters($manager, 100, date('Y-m-d'));
        return $this->render('KTUCountersBundle:Tops:viewTops.html.twig', array('counters' => $counters));
    }
}