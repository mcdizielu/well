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
use WellCommerce\Bundle\AppBundle\Entity\Currency;
use WellCommerce\Bundle\AppBundle\Entity\Dimension;
use WellCommerce\Bundle\AppBundle\Entity\ShopCollectionAwareTrait;
use WellCommerce\Bundle\AppBundle\Entity\Tax;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Enableable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Sortable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class ShippingMethod
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ShippingMethod implements EntityInterface
{
    use Identifiable;
    use Enableable;
    use Sortable;
    use Translatable;
    use Timestampable;
    use Blameable;
    use ShopCollectionAwareTrait;
    
    protected $calculator      = '';
    protected $optionsProvider = '';
    protected $countries       = [];
    
    /**
     * @var Dimension
     */
    protected $boxDimension;
    
    /**
     * @var Currency
     */
    protected $currency;
    
    /**
     * @var Tax
     */
    protected $tax;
    
    /**
     * @var Collection
     */
    protected $costs;
    
    /**
     * @var Collection
     */
    protected $paymentMethods;
    
    /**
     * @var Collection
     */
    protected $clientGroups;
    
    public function __construct()
    {
        $this->costs          = new ArrayCollection();
        $this->paymentMethods = new ArrayCollection();
        $this->shops          = new ArrayCollection();
        $this->clientGroups   = new ArrayCollection();
        $this->boxDimension   = new Dimension();
    }
    
    public function getCalculator(): string
    {
        return $this->calculator;
    }
    
    public function setCalculator(string $calculator)
    {
        $this->calculator = $calculator;
    }
    
    public function getOptionsProvider(): string
    {
        return $this->optionsProvider;
    }
    
    public function setOptionsProvider(string $optionsProvider)
    {
        $this->optionsProvider = $optionsProvider;
    }
    
    public function getCosts(): Collection
    {
        return $this->costs;
    }
    
    public function setCosts(Collection $costs)
    {
        $this->costs = $costs;
    }
    
    public function getPaymentMethods(): Collection
    {
        return $this->paymentMethods;
    }
    
    public function getCountries(): array
    {
        return $this->countries;
    }
    
    public function setCountries(array $countries)
    {
        $this->countries = $countries;
    }
    
    public function getCurrency()
    {
        return $this->currency;
    }
    
    public function setCurrency(Currency $currency = null)
    {
        $this->currency = $currency;
    }
    
    public function getTax()
    {
        return $this->tax;
    }
    
    public function setTax(Tax $tax = null)
    {
        $this->tax = $tax;
    }
    
    public function getClientGroups(): Collection
    {
        return $this->clientGroups;
    }
    
    public function setClientGroups(Collection $clientGroups)
    {
        $this->clientGroups = $clientGroups;
    }
    
    public function getBoxDimension(): Dimension
    {
        return $this->boxDimension;
    }
    
    public function setBoxDimension(Dimension $boxDimension)
    {
        $this->boxDimension = $boxDimension;
    }
    
    public function translate($locale = null, $fallbackToDefault = true): ShippingMethodTranslation
    {
        return $this->doTranslate($locale, $fallbackToDefault);
    }
}
