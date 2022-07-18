<?php

namespace App\Service;

use App\Entity\Ticket;
use App\Entity\Commande;
use App\Repository\TicketRepository;

class GenerateTicketService {

    private $ticketRepository;
    private $printPdfService;

    public function __construct( TicketRepository $ticketRepository,PrintPdfService $printPdfService ) {
        $this->ticketRepository = $ticketRepository;
        $this->printPdfService = $printPdfService;
    }

    public function generateTicket(Commande $commande){

        $ticket = new Ticket();
        $ticket->setCommande($commande);

        $count = $this->ticketRepository->getTicketsCount()[0]["count"] + 1;
        $ticket->generateReference($count);

        $pdf = $this->printPdfService->pdfAction($ticket);
        $ticket->setPdfFile(\base64_encode($pdf));

        return $ticket;

    }
}