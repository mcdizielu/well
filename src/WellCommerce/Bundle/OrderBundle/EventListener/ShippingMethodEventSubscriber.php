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

namespace WellCommerce\Bundle\OrderBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use WellCommerce\Bundle\AppBundle\Repository\CountryRepository;
use WellCommerce\Bundle\AppBundle\Service\Tax\Helper\TaxHelperInterface;
use WellCommerce\Bundle\OrderBundle\Entity\ShippingMethod;
use WellCommerce\Bundle\OrderBundle\Entity\ShippingMethodCost;

/**
 * Class ShippingMethodEventSubscriber
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class ShippingMethodEventSubscriber implements EventSubscriber
{
    /**
     * @var CountryRepository
     */
    private $countryRepository;
    
    /**
     * @var TaxHelperInterface
     */
    private $taxHelper;
    
    public function __construct(CountryRepository $countryRepository, TaxHelperInterface $taxHelper)
    {
        $this->countryRepository = $countryRepository;
        $this->taxHelper         = $taxHelper;
    }
    
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->onShippingMethodCostBeforeSave($args);
    }
    
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->onShippingMethodCostBeforeSave($args);
    }
    
    public function onShippingMethodCostBeforeSave(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof ShippingMethodCost) {
            $shippingMethod = $entity->getShippingMethod();
            $cost           = $entity->getCost();
            $grossAmount    = $cost->getGrossAmount();
            $taxRate        = $shippingMethod->getTax()->getValue();
            $netAmount      = $this->taxHelper->calculateNetPrice($grossAmount, $taxRate);
            
            $cost->setTaxRate($taxRate);
            $cost->setTaxAmount($grossAmount - $netAmount);
            $cost->setNetAmount($netAmount);
            $cost->setCurrency($shippingMethod->getCurrency()->getCode());
        }
        
        if ($entity instanceof ShippingMethod) {
            $availableCountries = $this->countryRepository->all();
            $countries          = array_filter($entity->getCountries(), function ($k) use ($availableCountries) {
                return array_key_exists($k, $availableCountries);
            }, ARRAY_FILTER_USE_KEY);
            
            $entity->setCountries($countries);
        }
    }
    
    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'preUpdate',
        ];
    }
}
