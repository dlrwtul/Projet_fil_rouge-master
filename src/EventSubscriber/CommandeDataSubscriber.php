<?php

namespace App\EventSubscriber;

use App\Entity\Commande;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Service\EtatService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CommandeDataSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['getData', EventPriorities::PRE_WRITE]
        ];
    }

    public function getData(ViewEvent $event)
    {
        $commande = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$commande instanceof Commande || Request::METHOD_PUT !== $method) {
            return;
        }

        $newEtat = $event->getRequest()->attributes->get("_route_params")["etat"];
        $previousEtat = $commande->getEtat();
        $error = false;


        switch ($newEtat) {
            case EtatService::ETAT_TERMINE:
                if ($previousEtat != EtatService::ETAT_EN_COURS) {
                    $error = true;
                }
                break;
            case EtatService::ETAT_EN_COURS_DE_LIVRAISON:
                if ($previousEtat != EtatService::ETAT_TERMINE || $commande->isIsALivrer() != 1) {
                    $error = true;
                }
                break;
            case EtatService::ETAT_VALIDE:
                if (($previousEtat != EtatService::ETAT_TERMINE || $commande->isIsALivrer() != 0) && ($previousEtat != EtatService::ETAT_EN_COURS_DE_LIVRAISON)) {
                    $error = true;
                }
                break;
            case EtatService::ETAT_ANNULE:
                if ($previousEtat == EtatService::ETAT_VALIDE || $previousEtat == EtatService::ETAT_ANNULE) {
                    $error = true;
                }
                break;
            default:
                return $event->setResponse(new Response("etat inconnu",Response::HTTP_BAD_REQUEST));
                break;
        }

        if ($error == 1) {
            return $event->setResponse(new Response("{ "."\n"."    'mess error' => 'changement d'etat incorrect'"."\n"."    'etat' => ".$previousEtat." }",Response::HTTP_BAD_REQUEST));
        } else {
            $commande->setEtat($newEtat);
        }
    }
}
