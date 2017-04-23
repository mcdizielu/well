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

use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\AppBundle\Entity\ClientGroup;
use WellCommerce\Bundle\AppBundle\Entity\MinimumOrderAmount;
use WellCommerce\Bundle\AppBundle\Service\Currency\Helper\CurrencyHelperInterface;
use WellCommerce\Bundle\OrderBundle\Entity\Order;

/**
 * Class MinimumAmountVisitor
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class MinimumAmountVisitor implements OrderVisitorInterface
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
        $minimumOrderAmount = $this->getMinimumOrderAmount($order);
        $baseCurrency       = strlen($minimumOrderAmount->getCurrency()) ? $minimumOrderAmount->getCurrency() : null;
        $targetCurrency     = $order->getCurrency();
        $value              = $this->currencyHelper->convert($minimumOrderAmount->getValue(), $baseCurrency, $targetCurrency);
        
        $order->getMinimumOrderAmount()->setCurrency($targetCurrency);
        $order->getMinimumOrderAmount()->setValue($value);
    }
    
    private function getMinimumOrderAmount(Order $order): MinimumOrderAmount
    {
        $client = $order->getClient();
        
        if ($client instanceof Client) {
            if ($client->getMinimumOrderAmount()->getValue() > 0) {
                return $client->getMinimumOrderAmount();
            }
            
            $clientGroup = $client->getClientGroup();
            
            if ($clientGroup instanceof ClientGroup) {
                if ($clientGroup->getMinimumOrderAmount()->getValue() > 0) {
                    return $clientGroup->getMinimumOrderAmount();
                }
            }
        }
        
        return $order->getShop()->getMinimumOrderAmount();
    }
}
