<?php
/*
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\InvoiceBundle\Entity;

use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Knp\DoctrineBehaviors\Model\Blameable\Blameable;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use WellCommerce\Bundle\AppBundle\Entity\ClientBillingAddress;
use WellCommerce\Bundle\AppBundle\Entity\ShopAwareTrait;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;
use WellCommerce\Bundle\CoreBundle\Helper\Helper;
use WellCommerce\Bundle\OrderBundle\Entity\OrderAwareTrait;
use WellCommerce\Extra\InvoiceBundle\Entity\InvoiceExtraTrait;

/**
 * Class Invoice
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class Invoice implements EntityInterface
{
    use Identifiable;
    use Timestampable;
    use Blameable;
    use OrderAwareTrait;
    use ShopAwareTrait;
    use InvoiceExtraTrait;
    
    const DEFAULT_PROCESSOR = 'generic';
    
    protected $guid               = '';
    protected $number             = '';
    protected $currency           = '';
    protected $currencyRate       = 1.0000;
    protected $shippingMethodName = '';
    protected $paymentMethodName  = '';
    protected $date;
    protected $dueDate;
    protected $paid               = true;
    protected $amountDue          = 0.00;
    protected $amountPaid         = 0.00;
    protected $signature          = '';
    protected $processor          = self::DEFAULT_PROCESSOR;
    
    /**
     * @var Collection
     */
    protected $items;
    
    /**
     * @var ClientBillingAddress
     */
    protected $billingAddress;
    
    public function __construct()
    {
        $this->items   = new ArrayCollection();
        $this->guid    = Helper::generateGuid();
        $this->date    = Carbon::now();
        $this->dueDate = Carbon::now();
    }
    
    public function getGuid(): string
    {
        return $this->guid;
    }
    
    public function setGuid(string $guid)
    {
        $this->guid = $guid;
    }
    
    public function getNumber(): string
    {
        return $this->number;
    }
    
    public function setNumber(string $number)
    {
        $this->number = $number;
    }
    
    public function getCurrency(): string
    {
        return $this->currency;
    }
    
    public function setCurrency(string $currency)
    {
        $this->currency = $currency;
    }
    
    public function getCurrencyRate(): float
    {
        return $this->currencyRate;
    }
    
    public function setCurrencyRate(float $currencyRate)
    {
        $this->currencyRate = $currencyRate;
    }
    
    public function getShippingMethodName(): string
    {
        return $this->shippingMethodName;
    }
    
    public function setShippingMethodName(string $shippingMethodName)
    {
        $this->shippingMethodName = $shippingMethodName;
    }
    
    public function getPaymentMethodName(): string
    {
        return $this->paymentMethodName;
    }
    
    public function setPaymentMethodName(string $paymentMethodName)
    {
        $this->paymentMethodName = $paymentMethodName;
    }
    
    public function getDate(): \DateTime
    {
        return $this->date;
    }
    
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }
    
    public function getDueDate(): \DateTime
    {
        return $this->dueDate;
    }
    
    public function setDueDate(\DateTime $dueDate)
    {
        $this->dueDate = $dueDate;
    }
    
    public function isPaid(): bool
    {
        return $this->paid;
    }
    
    public function setPaid(bool $paid)
    {
        $this->paid = $paid;
    }
    
    public function getAmountDue(): float
    {
        return $this->amountDue;
    }
    
    public function setAmountDue(float $amountDue)
    {
        $this->amountDue = $amountDue;
    }
    
    public function getAmountPaid(): float
    {
        return $this->amountPaid;
    }
    
    public function setAmountPaid(float $amountPaid)
    {
        $this->amountPaid = $amountPaid;
    }
    
    public function getItems(): Collection
    {
        return $this->items;
    }
    
    public function setItems(Collection $items)
    {
        $this->items = $items;
    }
    
    public function getBillingAddress(): ClientBillingAddress
    {
        return $this->billingAddress;
    }
    
    public function setBillingAddress(ClientBillingAddress $billingAddress)
    {
        $this->billingAddress = $billingAddress;
    }
    
    public function getSignature(): string
    {
        return $this->signature;
    }
    
    public function setSignature(string $signature)
    {
        $this->signature = $signature;
    }
    
    public function getProcessor(): string
    {
        return $this->processor;
    }
    
    public function setProcessor(string $processor)
    {
        $this->processor = $processor;
    }
}
