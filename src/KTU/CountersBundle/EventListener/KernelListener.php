<?php

namespace KTU\CountersBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use KTU\CountersBundle\Kernel\Handler;

/**
 * Class KernelListener. Kernel listener'is, vykdomas prieš kiekvieną kontrolerį.
 * @package KTU\CountersBundle\EventListener
 */
class KernelListener extends ContainerAware implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(KernelEvents::CONTROLLER => array(
            array('core', 0),
        ));
    }

    /**
     * Vykdo veiksmus prieš kiekvieną kontrolerį
     * @param FilterControllerEvent $event
     */
    public function core(FilterControllerEvent $event)
    {
        // Ignore sub requests
        if (HttpKernel::MASTER_REQUEST != $event->getRequestType()) return;
        // Sukuriamas handler'is kuris ivykdys reikalingus veiksmus.
        $handler = new Handler($this->container, $event->getRequest());
        $handler->setLocale();
        $handler->preRender();
    }
}