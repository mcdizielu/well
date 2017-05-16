<?php

namespace WellCommerce\Bundle\InvoiceBundle\Processor;

use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\CoreBundle\Helper\Pdf\PdfHelperInterface;
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
    
    /**
     * @var PdfHelperInterface
     */
    private $pdfHelper;
    
    public function __construct(InvoiceNumberGeneratorInterface $invoiceNumberGenerator, PdfHelperInterface $pdfHelper)
    {
        $this->invoiceNumberGenerator = $invoiceNumberGenerator;
        $this->pdfHelper              = $pdfHelper;
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
    
    public function send(Invoice $invoice)
    {
    }
}
