<?php

namespace KTU\CountersBundle\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;

class ExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        $message = sprintf(
            'Error: %s with code: %s',
            $exception->getMessage(),
            $exception->getCode()
        );

        $response = new Response();
        $response->setContent($message);

        /*if ($exception instanceof HttpExceptionInterface) {
            $uri = $event->getRequest()->getBaseUrl();
            //$event->setResponse(new RedirectResponse($uri));
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);

        }
        $event->setResponse($response);*/
    }
}