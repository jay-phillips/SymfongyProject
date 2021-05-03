<?php

namespace App\EventSubscriber;


use App\Exception\EmptyBodyException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EmptyBodySubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['handleEmptyBody', EventPriorities::PRE_DESERIALIZE]
        ];
    }

    public function handleEmptyBody(RequestEvent $event)
    {
        $method = $event->getRequest()->getMethod();
        if (!in_array($method, [Request::METHOD_POST, Request::METHOD_PUT])){
            return;
        } 
        $data = $event->getRequest()->get('data');
        if (null === $data){
            throw new EmptyBodyException();
        }
    }
}

