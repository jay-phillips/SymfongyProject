<?php

namespace App\EventSubscriber;


use App\Entity\PublishedDateEntityInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class PublishedDateEntitySubscriber implements EventSubscriberInterface
{

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return string[]
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setDatePublished', EventPriorities::PRE_VALIDATE]
        ];

    }

    public function setDatePublished(ViewEvent $event)
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ((!$entity instanceof PublishedDateEntityInterface) || Request::METHOD_POST !== $method)
        {
            return;
        }
        
        $entity->setPublished(new \DateTime());
        
        

    }

}

