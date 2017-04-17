<?php

namespace WellCommerce\Bundle\InvoiceBundle\Entity;

use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class InvoiceItem
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class InvoiceItem implements EntityInterface
{
    use Identifiable;
    
    protected $description = '';
    protected $netAmount   = 0;
    protected $grossAmount = 0;
    protected $taxAmount   = 0;
    protected $taxRate     = 0;
    protected $currency    = '';
    protected $quantity    = 1;
    
    /**
     * @var Invoice
     */
    protected $invoice;
    
    public function getDescription(): string
    {
        return $this->description;
    }
    
    public function setDescription(string $description)
    {
        $this->description = $description;
    }
    
    public function getNetAmount(): int
    {
        return $this->netAmount;
    }
    
    public function setNetAmount(int $netAmount)
    {
        $this->netAmount = $netAmount;
    }
    
    public function getGrossAmount(): int
    {
        return $this->grossAmount;
    }
    
    public function setGrossAmount(int $grossAmount)
    {
        $this->grossAmount = $grossAmount;
    }
    
    public function getTaxAmount(): int
    {
        return $this->taxAmount;
    }
    
    public function setTaxAmount(int $taxAmount)
    {
        $this->taxAmount = $taxAmount;
    }
    
    public function getTaxRate(): int
    {
        return $this->taxRate;
    }
    
    public function setTaxRate(int $taxRate)
    {
        $this->taxRate = $taxRate;
    }
    
    public function getCurrency(): string
    {
        return $this->currency;
    }
    
    public function setCurrency(string $currency)
    {
        $this->currency = $currency;
    }
    
    public function getQuantity(): int
    {
        return $this->quantity;
    }
    
    public function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;
    }
    
    public function getInvoice(): Invoice
    {
        return $this->invoice;
    }
    
    public function setInvoice(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }
}
