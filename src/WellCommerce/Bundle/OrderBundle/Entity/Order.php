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
namespace WellCommerce\Bundle\OrderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\AppBundle\Entity\ClientBillingAddress;
use WellCommerce\Bundle\AppBundle\Entity\ClientContactDetails;
use WellCommerce\Bundle\AppBundle\Entity\ClientDetails;
use WellCommerce\Bundle\AppBundle\Entity\ClientShippingAddress;
use WellCommerce\Bundle\AppBundle\Entity\MinimumOrderAmount;
use WellCommerce\Bundle\AppBundle\Entity\Shop;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;
use WellCommerce\Bundle\CouponBundle\Entity\Coupon;
use WellCommerce\Bundle\OrderBundle\Entity\Extra\OrderExtraTrait;
use WellCommerce\Bundle\OrderBundle\Visitor\OrderVisitorInterface;

/**
 * Class Order
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class Order implements EntityInterface
{
    use Identifiable;
    use Timestampable;
    use OrderExtraTrait;
    
    protected $confirmed            = false;
    protected $number               = null;
    protected $currency             = null;
    protected $currencyRate         = 1.0000;
    protected $sessionId            = '';
    protected $shippingMethodOption = null;
    protected $comment              = '';
    protected $issueInvoice         = false;
    protected $conditionsAccepted   = false;
    
    /**
     * @var null|Client
     */
    protected $client;
    
    /**
     * @var ClientDetails
     */
    protected $clientDetails;
    
    /**
     * @var ClientContactDetails
     */
    protected $contactDetails;
    
    /**
     * @var ClientBillingAddress
     */
    protected $billingAddress;
    
    /**
     * @var ClientShippingAddress
     */
    protected $shippingAddress;
    
    /**
     * @var Coupon
     */
    protected $coupon;
    
    /**
     * @var OrderStatus
     */
    protected $currentStatus;
    
    /**
     * @var OrderSummary
     */
    protected $summary;
    
    /**
     * @var OrderProductTotal
     */
    protected $productTotal;
    
    /**
     * @var MinimumOrderAmount
     */
    protected $minimumOrderAmount;
    
    /**
     * @var Collection
     */
    protected $products;
    
    /**
     * @var Collection
     */
    protected $payments;
    
    /**
     * @var Collection
     */
    protected $orderStatusHistory;
    
    /**
     * @var Collection
     */
    protected $modifiers;
    
    /**
     * @var Shop
     */
    protected $shop;
    
    /**
     * @var ShippingMethod
     */
    protected $shippingMethod;
    
    /**
     * @var PaymentMethod
     */
    protected $paymentMethod;
    
    public function __construct()
    {
        $this->products           = new ArrayCollection();
        $this->modifiers          = new ArrayCollection();
        $this->payments           = new ArrayCollection();
        $this->orderStatusHistory = new ArrayCollection();
        $this->productTotal       = new OrderProductTotal();
        $this->summary            = new OrderSummary();
        $this->clientDetails      = new ClientDetails();
        $this->contactDetails     = new ClientContactDetails();
        $this->billingAddress     = new ClientBillingAddress();
        $this->shippingAddress    = new ClientShippingAddress();
        $this->minimumOrderAmount = new MinimumOrderAmount();
    }
    
    public function getClient()
    {
        return $this->client;
    }
    
    public function setClient(Client $client = null)
    {
        $this->client = $client;
    }
    
    public function getClientDetails(): ClientDetails
    {
        return $this->clientDetails;
    }
    
    public function setClientDetails(ClientDetails $clientDetails)
    {
        $this->clientDetails = $clientDetails;
    }
    
    public function getContactDetails(): ClientContactDetails
    {
        return $this->contactDetails;
    }
    
    public function setContactDetails(ClientContactDetails $contactDetails)
    {
        $this->contactDetails = $contactDetails;
    }
    
    public function getBillingAddress(): ClientBillingAddress
    {
        return $this->billingAddress;
    }
    
    public function setBillingAddress(ClientBillingAddress $billingAddress)
    {
        $this->billingAddress = $billingAddress;
    }
    
    public function getShippingAddress(): ClientShippingAddress
    {
        return $this->shippingAddress;
    }
    
    public function setShippingAddress(ClientShippingAddress $shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;
    }
    
    public function isConfirmed(): bool
    {
        return $this->confirmed;
    }
    
    public function setConfirmed(bool $confirmed)
    {
        $this->confirmed = $confirmed;
    }
    
    public function getNumber()
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
    
    public function getSessionId(): string
    {
        return $this->sessionId;
    }
    
    public function setSessionId(string $sessionId)
    {
        $this->sessionId = $sessionId;
    }
    
    public function getCoupon()
    {
        return $this->coupon;
    }
    
    public function setCoupon(Coupon $coupon = null)
    {
        $this->coupon = $coupon;
    }
    
    public function getProducts(): Collection
    {
        return $this->products;
    }
    
    public function setProducts(Collection $products)
    {
        $this->products = $products;
    }
    
    public function getProductTotal(): OrderProductTotal
    {
        return $this->productTotal;
    }
    
    public function setProductTotal(OrderProductTotal $productTotal)
    {
        $this->productTotal = $productTotal;
    }
    
    public function hasModifier(string $name): bool
    {
        return $this->modifiers->containsKey($name);
    }
    
    public function removeModifier(string $name)
    {
        $this->modifiers->remove($name);
    }
    
    public function getModifier(string $name): OrderModifier
    {
        return $this->modifiers->get($name);
    }
    
    public function getModifiers(): Collection
    {
        return $this->modifiers;
    }
    
    public function getSummary(): OrderSummary
    {
        return $this->summary;
    }
    
    public function setSummary(OrderSummary $summary)
    {
        $this->summary = $summary;
    }
    
    public function getCurrentStatus()
    {
        return $this->currentStatus;
    }
    
    public function setCurrentStatus(OrderStatus $currentStatus = null)
    {
        $this->currentStatus = $currentStatus;
    }
    
    public function getOrderStatusHistory(): Collection
    {
        return $this->orderStatusHistory;
    }
    
    public function getComment(): string
    {
        return $this->comment;
    }
    
    public function setComment(string $comment)
    {
        $this->comment = $comment;
    }
    
    public function getPayments(): Collection
    {
        return $this->payments;
    }
    
    public function acceptVisitor(OrderVisitorInterface $visitor)
    {
        $visitor->visitOrder($this);
    }
    
    public function isEmpty(): bool
    {
        return 0 === $this->productTotal->getQuantity();
    }
    
    public function getShippingMethodOption()
    {
        return $this->shippingMethodOption;
    }
    
    public function setShippingMethodOption($shippingMethodOption)
    {
        $this->shippingMethodOption = $shippingMethodOption;
    }
    
    public function isConditionsAccepted(): bool
    {
        return $this->conditionsAccepted;
    }
    
    public function setConditionsAccepted(bool $conditionsAccepted)
    {
        $this->conditionsAccepted = $conditionsAccepted;
    }
    
    public function isIssueInvoice(): bool
    {
        return $this->issueInvoice;
    }
    
    public function setIssueInvoice(bool $issueInvoice)
    {
        $this->issueInvoice = $issueInvoice;
    }
    
    public function getShop(): Shop
    {
        return $this->shop;
    }
    
    public function setShop(Shop $shop)
    {
        $this->shop = $shop;
    }
    
    public function getShippingMethod()
    {
        return $this->shippingMethod;
    }
    
    public function setShippingMethod(ShippingMethod $shippingMethod = null)
    {
        $this->shippingMethod = $shippingMethod;
    }
    
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }
    
    public function setPaymentMethod(PaymentMethod $paymentMethod = null)
    {
        $this->paymentMethod = $paymentMethod;
    }
    
    public function getMinimumOrderAmount(): MinimumOrderAmount
    {
        return $this->minimumOrderAmount;
    }
    
    public function setMinimumOrderAmount(MinimumOrderAmount $minimumOrderAmount)
    {
        $this->minimumOrderAmount = $minimumOrderAmount;
    }
    
    public function hasMinimumValue(): bool
    {
        return $this->getSummary()->getGrossAmount() >= $this->minimumOrderAmount->getValue();
    }
    
    public function hasMethods(): bool
    {
        return $this->shippingMethod instanceof ShippingMethod && $this->paymentMethod instanceof PaymentMethod;
    }
    
    public function isConfirmable(): bool
    {
        return !$this->isEmpty() && $this->hasMinimumValue() && $this->hasMethods();
    }
}
