<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Produit;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductDataSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['getData', EventPriorities::POST_WRITE],
        ];
    }

    public function getData(ViewEvent $event): void
    {
        $product = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$product instanceof Produit || Request::METHOD_POST !== $method) {
            return;
        }

        dd($product);

    }
}
