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

/**
 * Class CurrencyOrderVisitor
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class CurrencyOrderVisitor implements OrderVisitorInterface
{
    /**
     * @var CurrencyHelperInterface
     */
    private $currencyHelper;
    
    public function __construct(CurrencyHelperInterface $currencyHelper)
    {
        $this->currencyHelper = $currencyHelper;
    }
    
    public function visitOrder(Order $order)
    {
        $currency     = $order->getCurrency();
        $currencyRate = $this->currencyHelper->getCurrencyRate($currency);
        $order->setCurrencyRate($currencyRate);
    }
}
