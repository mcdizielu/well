<?php

namespace WellCommerce\Bundle\InvoiceBundle\Processor;

use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\InvoiceBundle\Entity\Invoice;

/**
 * Class GenericInvoiceProcessor
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class GenericInvoiceProcessor implements InvoiceProcessorInterface
{
    public function getAlias(): string
    {
        return 'generic';
    }
    
    public function save(Invoice $invoice)
    {
    
    }
    
    public function void(Invoice $invoice)
    {
    
    }
    
    public function download(Invoice $invoice): Response
    {
    
    }
}
