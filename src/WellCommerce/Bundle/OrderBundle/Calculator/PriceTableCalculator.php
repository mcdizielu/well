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

namespace WellCommerce\Bundle\OrderBundle\Calculator;

use Doctrine\Common\Collections\Collection;
use WellCommerce\Bundle\AppBundle\Helper\CurrencyHelperInterface;
use WellCommerce\Bundle\OrderBundle\Entity\ShippingMethod;
use WellCommerce\Bundle\OrderBundle\Entity\ShippingMethodCost;

/**
 * Class PriceTableCalculator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class PriceTableCalculator implements ShippingCalculatorInterface
{
    /**
     * @var CurrencyHelperInterface
     */
    private $currencyHelper;
    
    public function __construct(CurrencyHelperInterface $currencyHelper)
    {
        $this->currencyHelper = $currencyHelper;
    }
    
    public function calculate(ShippingMethod $shippingMethod, ShippingSubjectInterface $subject): Collection
    {
        $ranges         = $shippingMethod->getCosts();
        $baseCurrency   = $subject->getCurrency();
        $targetCurrency = $shippingMethod->getCurrency()->getCode();
        $grossAmount    = $this->currencyHelper->convert($subject->getGrossPrice(), $baseCurrency, $targetCurrency);
        
        return $ranges->filter(function (ShippingMethodCost $cost) use ($grossAmount) {
            return ($cost->getRangeFrom() <= $grossAmount && $cost->getRangeTo() >= $grossAmount);
        });
    }
    
    public function getAlias(): string
    {
        return 'price_table';
    }
}
