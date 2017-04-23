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

namespace WellCommerce\Bundle\OrderBundle\Provider;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\AppBundle\Entity\Shop;
use WellCommerce\Bundle\AppBundle\Service\Shop\Storage\ShopStorageInterface;
use WellCommerce\Bundle\CoreBundle\Helper\Security\SecurityHelperInterface;
use WellCommerce\Bundle\OrderBundle\Calculator\ShippingCalculatorInterface;
use WellCommerce\Bundle\OrderBundle\Calculator\ShippingSubjectInterface;
use WellCommerce\Bundle\OrderBundle\Entity\ShippingMethod;
use WellCommerce\Bundle\OrderBundle\Entity\ShippingMethodCost;
use WellCommerce\Bundle\OrderBundle\Exception\CalculatorNotFoundException;
use WellCommerce\Bundle\OrderBundle\Repository\ShippingMethodRepositoryInterface;

/**
 * Class ShippingMethodProvider
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class ShippingMethodProvider implements ShippingMethodProviderInterface
{
    /**
     * @var ShippingMethodRepositoryInterface
     */
    private $repository;
    
    /**
     * @var Collection
     */
    private $calculators;
    
    /**
     * @var ShopStorageInterface
     */
    private $shopStorage;
    
    /**
     * @var SecurityHelperInterface
     */
    private $securityHelper;
    
    public function __construct(
        ShippingMethodRepositoryInterface $repository,
        Collection $calculators,
        ShopStorageInterface $shopStorage,
        SecurityHelperInterface $securityHelper
    ) {
        $this->repository     = $repository;
        $this->calculators    = $calculators;
        $this->shopStorage    = $shopStorage;
        $this->securityHelper = $securityHelper;
    }
    
    public function getCosts(ShippingSubjectInterface $subject): Collection
    {
        $methods    = $this->getShippingMethods($subject);
        $collection = new ArrayCollection();
        
        $methods->map(function (ShippingMethod $shippingMethod) use ($subject, $collection) {
            $costs = $this->getShippingMethodCosts($shippingMethod, $subject);
            
            $costs->map(function (ShippingMethodCost $cost) use ($collection) {
                $collection->add($cost);
            });
        });
        
        return $collection;
    }
    
    public function getShippingMethodCosts(ShippingMethod $method, ShippingSubjectInterface $subject): Collection
    {
        $calculator = $this->getCalculator($method);
        $country    = $subject->getCountry();
        $countries  = $method->getCountries();
        $shop       = $this->getCurrentShop($subject);
        
        if (strlen($country) && count($countries) && !in_array($country, $countries)) {
            return new ArrayCollection();
        }
        
        if (false === $method->getShops()->contains($shop)) {
            return new ArrayCollection();
        }
        
        return $calculator->calculate($method, $subject);
    }
    
    private function getCalculator(ShippingMethod $shippingMethod): ShippingCalculatorInterface
    {
        $calculator = $shippingMethod->getCalculator();
        
        if (false === $this->calculators->containsKey($calculator)) {
            throw new CalculatorNotFoundException($calculator);
        }
        
        return $this->calculators->get($calculator);
    }
    
    private function getShippingMethods(ShippingSubjectInterface $subject): Collection
    {
        $methods = $this->repository->getShippingMethods();
        $country = $subject->getCountry();
        $shop    = $this->getCurrentShop($subject);
        $client  = $this->securityHelper->getCurrentClient();
        
        return $methods->filter(function (ShippingMethod $method) use ($country, $shop, $client) {
            if (strlen($country) && count($method->getCountries()) && !in_array($country, $method->getCountries())) {
                return false;
            }
            
            return $method->getShops()->contains($shop) && $this->isShippingMethodAvailableForClient($method, $client);
        });
    }
    
    private function getCurrentShop(ShippingSubjectInterface $subject): Shop
    {
        if (!$subject->getShop() instanceof Shop) {
            return $this->shopStorage->getCurrentShop();
        }
        
        return $subject->getShop();
    }
    
    private function isShippingMethodAvailableForClient(ShippingMethod $method, Client $client = null)
    {
        $clientGroups = $method->getClientGroups();
        $clientGroup  = null !== $client ? $client->getClientGroup() : null;
        
        if ($clientGroups->count()) {
            return $clientGroups->contains($clientGroup);
        }
        
        return true;
    }
}
