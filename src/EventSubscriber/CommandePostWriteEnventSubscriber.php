<?php

namespace App\EventSubscriber;

use App\Entity\Commande;
use App\Service\EtatService;
use App\Service\MailerService;
use App\Repository\TicketRepository;
use App\Service\GenerateTicketService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CommandePostWriteEnventSubscriber implements EventSubscriberInterface
{
    private $mailerService;
    private $generateTicketService;
    private $serializer;
    private $ticketRepository;

    public function __construct(
        GenerateTicketService $generateTicketService,
        MailerService $mailerService,
        SerializerInterface $serializer,
        TicketRepository $ticketRepository
    )
    {
        $this->mailerService = $mailerService;
        $this->generateTicketService = $generateTicketService;
        $this->serializer = $serializer;
        $this->ticketRepository = $ticketRepository;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['getData', EventPriorities::POST_WRITE],
        ];
    }

    public function getData(ViewEvent $event)
    {
        $commande = $event->getControllerResult();

        $method = $event->getRequest()->getMethod();
        if (!$commande instanceof Commande || Request::METHOD_PUT !== $method) {
            return;
        }
        
        if ($commande->getEtat() == EtatService::ETAT_EN_COURS_DE_LIVRAISON || ($commande->isIsALivrer() == 0 && $commande->getEtat() == EtatService::ETAT_VALIDE)) {
            
            $ticket = $this->generateTicketService->generateTicket($commande);
            $this->mailerService->sendEmail($commande->getClient()->getLogin());

            $this->ticketRepository->add($ticket,true);

            $json = $this->serializer->serialize($ticket,'json',['groups' => ['ticket:read']]);
            
            return $event->setResponse(new Response($json,Response::HTTP_OK));
        }
    }
}
