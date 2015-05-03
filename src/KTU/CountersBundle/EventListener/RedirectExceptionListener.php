<?php

namespace KTU\CountersBundle\EventListener;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RedirectExceptionListener {

    /**
     * @var Router
     */
    protected $router;

    function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
     */
    public function checkRedirect(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if ($exception instanceof NotFoundHttpException) {
            $url = $this->router->generate('ktu_counters_homepage');
            $event->setResponse(new RedirectResponse($url));
        }
    }
} 