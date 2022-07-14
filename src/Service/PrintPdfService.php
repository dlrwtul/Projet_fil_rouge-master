<?php

namespace App\Service;

use Knp\Snappy\Pdf;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;

class PrintPdfService 
{
    private $knpSnappyPdf;

    public function __construct(Pdf $knpSnappyPdf) {
        $this->knpSnappyPdf = $knpSnappyPdf;
    }

    public function PrintAsPdf($array)
    {
       // return $this->knpSnappyPdf->getOutputFromHtml($html);
    }
}