<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class pdfService
{
    private $domPdf;

    public function __construct()
    {
        $this->domPdf = new Dompdf();
        $pdfOptions = new Options();
        // Set the base path only if necessary (if assets are not in the "public" directory)
        $pdfOptions->set('isPhpEnabled', true);
        $pdfOptions->set('isRemoteEnabled', true);
        $this->domPdf->setOptions($pdfOptions);
    }

    public function showPdfFile($html)
    {
        $this->domPdf->loadHtml($html);
        $this->domPdf->render();
        $this->domPdf->stream("details.pdf", ['attachment' => false]);
    }

    public function generateBinaryPDF($html)
    {
        $this->domPdf->loadHtml($html);
        $this->domPdf->render();
        return $this->domPdf->output();
    }

}
