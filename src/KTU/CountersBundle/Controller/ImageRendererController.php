<?php

namespace KTU\CountersBundle\Controller;

use KTU\CountersBundle\Components\ColorConverter;
use KTU\CountersBundle\Entity\Counters;
use KTU\CountersBundle\Components\CounterImageRenderer;
use KTU\CountersBundle\Entity\CounterStatistics;
use KTU\CountersBundle\Model\CounterStatisticsModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ImageRendererController, statistical counter's image renderer.
 * @package KTU\CountersBundle\Controller
 */
class ImageRendererController extends Controller
{
    /**
     * Renders statistical image by given counter's ID
     * @param Request $request
     * @param Counters $counter
     * @return Response
     */
    public function renderImageAction(Request $request, Counters $counter)
    {
        $manager = $this->getDoctrine()->getManager();
        $referer = $request->server->get('HTTP_REFERER');

        if (!empty($referer)) {
            $statsObj = new CounterStatistics();
            $statsObj->setCounter($counter);
            $statsObj->setIp($request->getClientIp());
            $statsObj->setDate(new \DateTime('now'));
            $manager->persist($statsObj);
            $manager->flush();
        }

        $stats_total = CounterStatisticsModel::getTotalStatsByCountersId($manager, $counter->getId());
        $stats_today = CounterStatisticsModel::getCountersVisitorsStatisticsByDate(
            $manager, $counter->getId(), date('Y-m-d'));
        $stats = CounterStatisticsModel::getLastStatsByCountersId($manager, $counter->getId(), -14);

        $image = new CounterImageRenderer(88, 31, $stats_today['total'], $stats_total['total']);
        $image->setCounterText($counter->getName());
        $image->setStatistics($stats);

        $image->setOption('background_color', ColorConverter::toColor($counter->getBackgroundColor()));
        $image->setOption('border_color', ColorConverter::toColor($counter->getBorderColor()));
        $image->setOption('text_color', ColorConverter::toColor($counter->getTextColor()));
        $image->setOption('unique_color', ColorConverter::toColor($counter->getUniqueColor()));
        $image->setOption('total_color', ColorConverter::toColor($counter->getTotalColor()));
        $image->setOption('bar_total_color', ColorConverter::toColor($counter->getBarTotalColor()));
        $image->setOption('bar_unique_color', ColorConverter::toColor($counter->getBarUniqueColor()));
        $image->setOption('transparent_background', $counter->getTransparentBackground());
        $image->render();

        return new Response($image->getImageString(), 200, array('Content-Type' => 'image/png'));
    }
}