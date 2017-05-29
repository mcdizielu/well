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
use Knp\DoctrineBehaviors\Model\Blameable\Blameable;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Knp\DoctrineBehaviors\Model\Translatable\Translatable;
use WellCommerce\Bundle\AppBundle\Entity\ShopCollectionAwareTrait;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Enableable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Sortable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;
use WellCommerce\Extra\OrderBundle\Entity\PaymentMethodExtraTrait;

/**
 * Class PaymentMethod
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class PaymentMethod implements EntityInterface
{
    use Identifiable;
    use Enableable;
    use Sortable;
    use Translatable;
    use Timestampable;
    use Blameable;
    use ShopCollectionAwareTrait;
    use PaymentMethodExtraTrait;
    
    /**
     * @var string
     */
    protected $processor = '';
    
    /**
     * @var array
     */
    protected $configuration = [];
    
    /**
     * @var OrderStatus
     */
    protected $paymentPendingOrderStatus;
    
    /**
     * @var OrderStatus
     */
    protected $paymentSuccessOrderStatus;
    
    /**
     * @var OrderStatus
     */
    protected $paymentFailureOrderStatus;
    
    /**
     * @var Collection
     */
    protected $shippingMethods;
    
    /**
     * @var Collection
     */
    protected $clientGroups;
    
    public function __construct()
    {
        $this->shippingMethods = new ArrayCollection();
        $this->shops           = new ArrayCollection();
        $this->clientGroups    = new ArrayCollection();
    }
    
    public function getProcessor(): string
    {
        return $this->processor;
    }
    
    public function setProcessor(string $processor)
    {
        $this->processor = $processor;
    }
    
    public function getShippingMethods(): Collection
    {
        return $this->shippingMethods;
    }
    
    public function setShippingMethods(Collection $shippingMethods)
    {
        $this->shippingMethods = $shippingMethods;
    }
    
    public function getConfiguration(): array
    {
        return $this->configuration;
    }
    
    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration;
    }
    
    public function getClientGroups(): Collection
    {
        return $this->clientGroups;
    }
    
    public function setClientGroups(Collection $clientGroups)
    {
        $this->clientGroups = $clientGroups;
    }
    
    public function getPaymentPendingOrderStatus()
    {
        return $this->paymentPendingOrderStatus;
    }
    
    public function setPaymentPendingOrderStatus(OrderStatus $paymentPendingOrderStatus)
    {
        $this->paymentPendingOrderStatus = $paymentPendingOrderStatus;
    }
    
    public function getPaymentSuccessOrderStatus()
    {
        return $this->paymentSuccessOrderStatus;
    }
    
    public function setPaymentSuccessOrderStatus(OrderStatus $paymentSuccessOrderStatus)
    {
        $this->paymentSuccessOrderStatus = $paymentSuccessOrderStatus;
    }
    
    public function getPaymentFailureOrderStatus()
    {
        return $this->paymentFailureOrderStatus;
    }
    
    public function setPaymentFailureOrderStatus(OrderStatus $paymentFailureOrderStatus)
    {
        $this->paymentFailureOrderStatus = $paymentFailureOrderStatus;
    }
    
    public function translate($locale = null, $fallbackToDefault = true): PaymentMethodTranslation
    {
        return $this->doTranslate($locale, $fallbackToDefault);
    }
}
