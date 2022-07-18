<?php

namespace App\Service;

use Knp\Snappy\Pdf;
use Twig\Environment;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;

class PrintPdfService 
{
    private $knpSnappyPdf;
    private $twig;

    public function __construct(Pdf $knpSnappyPdf, Environment $twig) {
        $this->knpSnappyPdf = $knpSnappyPdf;
        $this->twig = $twig;
    }

    public function pdfAction(Object $object)
    {
        $html = $this->twig->render('ticketPdf/ticket.pdf.html.twig', array(
            'ticket' => $object
        ));

        $pdfFile = $this->knpSnappyPdf->getOutputFromHtml($html, ['encoding' => 'UTF8']);
        
        return $pdfFile;
    }
}