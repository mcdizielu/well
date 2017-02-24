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

namespace WellCommerce\Bundle\OrderBundle\Visitor;

use WellCommerce\Bundle\AppBundle\Entity\Price;
use WellCommerce\Bundle\AppBundle\Helper\CurrencyHelperInterface;
use WellCommerce\Bundle\CatalogBundle\Helper\VariantHelperInterface;
use WellCommerce\Bundle\OrderBundle\Entity\Order;
use WellCommerce\Bundle\OrderBundle\Entity\OrderProduct;

/**
 * Class OrderProductVisitor
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class OrderProductVisitor implements OrderVisitorInterface
{
    /**
     * @var CurrencyHelperInterface
     */
    private $currencyHelper;
    
    /**
     * @var VariantHelperInterface
     */
    private $variantHelper;
    
    /**
     * OrderProductVisitor constructor.
     *
     * @param CurrencyHelperInterface $currencyHelper
     * @param VariantHelperInterface  $variantHelper
     */
    public function __construct(CurrencyHelperInterface $currencyHelper, VariantHelperInterface $variantHelper)
    {
        $this->currencyHelper = $currencyHelper;
        $this->variantHelper  = $variantHelper;
    }
    
    public function visitOrder(Order $order)
    {
        $order->getProducts()->map(function (OrderProduct $orderProduct) use ($order) {
            if (false === $orderProduct->isLocked()) {
                if ($this->checkOrderStock($order, $orderProduct)) {
                    $this->refreshOrderProductSellPrice($orderProduct);
                    $this->refreshOrderProductBuyPrice($orderProduct);
                    $this->refreshOrderProductVariantOptions($orderProduct);
                    $this->refreshOrderProductWeight($orderProduct);
                }
            }
        });
    }
    
    private function checkOrderStock(Order $order, OrderProduct $orderProduct): bool
    {
        $trackStock      = $orderProduct->getProduct()->getTrackStock();
        $orderedQuantity = $orderProduct->getQuantity();
        $stock           = $orderProduct->getCurrentStock();
        
        if ($trackStock && $orderedQuantity > $stock) {
            if ($stock > 0) {
                $orderProduct->setQuantity($stock);
            } else {
                $order->getProducts()->removeElement($orderProduct);
                $orderProduct->setOrder(null);
                
                return false;
            }
        }
        
        return true;
    }
    
    private function refreshOrderProductSellPrice(OrderProduct $orderProduct)
    {
        $baseSellPrice  = $orderProduct->getCurrentSellPrice();
        $baseCurrency   = $baseSellPrice->getCurrency();
        $targetCurrency = $orderProduct->getOrder()->getCurrency();
        $grossAmount    = $this->currencyHelper->convert($baseSellPrice->getFinalGrossAmount(), $baseCurrency, $targetCurrency);
        $netAmount      = $this->currencyHelper->convert($baseSellPrice->getFinalNetAmount(), $baseCurrency, $targetCurrency);
        $taxAmount      = $this->currencyHelper->convert($baseSellPrice->getFinalTaxAmount(), $baseCurrency, $targetCurrency);
        
        $sellPrice = new Price();
        $sellPrice->setCurrency($targetCurrency);
        $sellPrice->setGrossAmount($grossAmount);
        $sellPrice->setNetAmount($netAmount);
        $sellPrice->setTaxAmount($taxAmount);
        $sellPrice->setTaxRate($baseSellPrice->getTaxRate());
        
        $orderProduct->setSellPrice($sellPrice);
    }
    
    private function refreshOrderProductBuyPrice(OrderProduct $orderProduct)
    {
        $baseBuyPrice   = $orderProduct->getProduct()->getBuyPrice();
        $baseCurrency   = $baseBuyPrice->getCurrency();
        $targetCurrency = $orderProduct->getOrder()->getCurrency();
        $grossAmount    = $this->currencyHelper->convert($baseBuyPrice->getGrossAmount(), $baseCurrency, $targetCurrency);
        $netAmount      = $this->currencyHelper->convert($baseBuyPrice->getNetAmount(), $baseCurrency, $targetCurrency);
        $taxAmount      = $this->currencyHelper->convert($baseBuyPrice->getTaxAmount(), $baseCurrency, $targetCurrency);
        
        $buyPrice = new Price();
        $buyPrice->setCurrency($targetCurrency);
        $buyPrice->setGrossAmount($grossAmount);
        $buyPrice->setNetAmount($netAmount);
        $buyPrice->setTaxAmount($taxAmount);
        $buyPrice->setTaxRate($baseBuyPrice->getTaxRate());
        
        $orderProduct->setBuyPrice($buyPrice);
    }
    
    private function refreshOrderProductVariantOptions(OrderProduct $orderProduct)
    {
        if ($orderProduct->hasVariant()) {
            $options = $this->variantHelper->getVariantOptions($orderProduct->getVariant());
            $orderProduct->setOptions($options);
        }
    }
    
    private function refreshOrderProductWeight(OrderProduct $orderProduct)
    {
        $orderProduct->setWeight($orderProduct->getCurrentWeight());
    }
}
