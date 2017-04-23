<?php

namespace WellCommerce\Bundle\InvoiceBundle\Processor;

use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\InvoiceBundle\Entity\Invoice;
use WellCommerce\Bundle\InvoiceBundle\Generator\InvoiceNumberGeneratorInterface;

/**
 * Class GenericInvoiceProcessor
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class GenericInvoiceProcessor implements InvoiceProcessorInterface
{
    /**
     * @var InvoiceNumberGeneratorInterface
     */
    private $invoiceNumberGenerator;
    
    private $pdfGenerator;
    
    public function __construct(InvoiceNumberGeneratorInterface $invoiceNumberGenerator, Pdf $pdfGenerator)
    {
        $this->invoiceNumberGenerator = $invoiceNumberGenerator;
        $this->pdfGenerator           = $pdfGenerator;
    }
    
    public function getAlias(): string
    {
        return 'generic';
    }
    
    public function save(Invoice $invoice)
    {
        $this->invoiceNumberGenerator->generate($invoice);
    }
    
    public function void(Invoice $invoice)
    {
    
    }
    
    public function download(Invoice $invoice): Response
    {
    }
}
