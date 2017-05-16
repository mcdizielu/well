<?php

namespace WellCommerce\Bundle\InvoiceBundle\Processor;

use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\InvoiceBundle\Entity\Invoice;

/**
 * Interface InvoiceProcessorInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface InvoiceProcessorInterface
{
    public function getAlias(): string;
    
    public function save(Invoice $invoice);
    
    public function void(Invoice $invoice);
    
    public function download(Invoice $invoice): Response;
    
    public function send(Invoice $invoice);
}
