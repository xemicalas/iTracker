<?php

namespace KTU\CountersBundle\Controller;

use KTU\CountersBundle\Model\CountersModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class TopsController. Topų kontroleris.
 * @package KTU\CountersBundle\Controller
 */
class TopsController extends Controller
{
    /**
     * Atvaizduoja top svetaines rūšiuojant pagal unikalius šiandienos lankytojus
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showTopsAction()
    {
        $manager = $this->getDoctrine()->getManager();
        $counters = CountersModel::getTopCounters($manager, 100, date('Y-m-d'));
        return $this->render('KTUCountersBundle:Tops:viewTops.html.twig', array('counters' => $counters));
    }
}