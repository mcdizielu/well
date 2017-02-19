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

use WellCommerce\Bundle\AppBundle\Helper\CurrencyHelperInterface;
use WellCommerce\Bundle\OrderBundle\Entity\Order;
use WellCommerce\Bundle\OrderBundle\Entity\OrderModifier;
use WellCommerce\Bundle\OrderBundle\Entity\OrderSummary;

/**
 * Class OrderSummaryVisitor
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class OrderSummaryVisitor implements OrderVisitorInterface
{
    /**
     * @var CurrencyHelperInterface
     */
    private $helper;
    
    /**
     * CartSummaryVisitor constructor.
     *
     * @param CurrencyHelperInterface $helper
     */
    public function __construct(CurrencyHelperInterface $helper)
    {
        $this->helper = $helper;
    }
    
    public function visitOrder(Order $order)
    {
        $productTotal   = $order->getProductTotal();
        $modifiers      = $order->getModifiers();
        $targetCurrency = $order->getCurrency();
        
        $summary = new OrderSummary();
        $summary->setGrossAmount($productTotal->getGrossPrice());
        $summary->setNetAmount($productTotal->getNetPrice());
        $summary->setTaxAmount($productTotal->getTaxAmount());
        
        $modifiers->map(function (OrderModifier $modifier) use ($summary, $targetCurrency) {
            $baseCurrency = $modifier->getCurrency();
            $grossAmount  = $this->helper->convert($modifier->getGrossAmount(), $baseCurrency, $targetCurrency);
            $netAmount    = $this->helper->convert($modifier->getNetAmount(), $baseCurrency, $targetCurrency);
            $taxAmount    = $this->helper->convert($modifier->getTaxAmount(), $baseCurrency, $targetCurrency);
            
            if ($modifier->isSubtraction()) {
                $summary->setGrossAmount($summary->getGrossAmount() - $grossAmount);
                $summary->setNetAmount($summary->getNetAmount() - $netAmount);
                $summary->setTaxAmount($summary->getTaxAmount() - $taxAmount);
            } else {
                $summary->setGrossAmount($summary->getGrossAmount() + $grossAmount);
                $summary->setNetAmount($summary->getNetAmount() + $netAmount);
                $summary->setTaxAmount($summary->getTaxAmount() + $taxAmount);
            }
        });
        
        $order->setSummary($summary);
    }
}
