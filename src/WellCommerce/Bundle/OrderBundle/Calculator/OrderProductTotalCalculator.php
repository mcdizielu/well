<?php
/**
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

use WellCommerce\Bundle\AppBundle\Helper\CurrencyHelperInterface;
use WellCommerce\Bundle\OrderBundle\Entity\Order;
use WellCommerce\Bundle\OrderBundle\Entity\OrderProduct;

/**
 * Class OrderProductTotalCalculator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class OrderProductTotalCalculator implements OrderProductTotalCalculatorInterface
{
    /**
     * @var CurrencyHelperInterface
     */
    private $helper;
    
    /**
     * OrderProductTotalVisitor constructor.
     *
     * @param CurrencyHelperInterface $helper
     */
    public function __construct(CurrencyHelperInterface $helper)
    {
        $this->helper = $helper;
    }
    
    public function getTotalQuantity(Order $order): int
    {
        $quantity = 0;
        $order->getProducts()->map(function (OrderProduct $orderProduct) use (&$quantity) {
            $quantity += $orderProduct->getQuantity();
        });
        
        return $quantity;
    }
    
    public function getTotalWeight(Order $order): float
    {
        $weight = 0;
        $order->getProducts()->map(function (OrderProduct $orderProduct) use (&$weight) {
            $weight += $orderProduct->getWeight() * $orderProduct->getQuantity();
        });
        
        return $weight;
    }
    
    public function getTotalNetAmount(Order $order): float
    {
        $targetCurrency = $order->getCurrency();
        $net            = 0;
        $order->getProducts()->map(function (OrderProduct $orderProduct) use (&$net, $targetCurrency) {
            $sellPrice    = $orderProduct->getSellPrice();
            $baseCurrency = $sellPrice->getCurrency();
            $priceNet     = $sellPrice->getNetAmount();
            
            $net += $this->helper->convert($priceNet, $baseCurrency, $targetCurrency, $orderProduct->getQuantity());
        });
        
        return $net;
    }
    
    public function getTotalGrossAmount(Order $order): float
    {
        $targetCurrency = $order->getCurrency();
        $gross          = 0;
        $order->getProducts()->map(function (OrderProduct $orderProduct) use (&$gross, $targetCurrency) {
            $sellPrice    = $orderProduct->getSellPrice();
            $baseCurrency = $sellPrice->getCurrency();
            $priceGross   = $sellPrice->getGrossAmount();
            
            $gross += $this->helper->convert($priceGross, $baseCurrency, $targetCurrency, $orderProduct->getQuantity());
        });
        
        return $gross;
    }
    
    public function getTotalTaxAmount(Order $order): float
    {
        $targetCurrency = $order->getCurrency();
        $tax            = 0;
        $order->getProducts()->map(function (OrderProduct $orderProduct) use (&$tax, $targetCurrency) {
            $sellPrice    = $orderProduct->getSellPrice();
            $baseCurrency = $sellPrice->getCurrency();
            $taxAmount    = $sellPrice->getTaxAmount();
            
            $tax += $this->helper->convert($taxAmount, $baseCurrency, $targetCurrency, $orderProduct->getQuantity());
        });
        
        return $tax;
    }
}
