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

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\AppBundle\Service\Currency\Helper\CurrencyHelperInterface;
use WellCommerce\Bundle\CoreBundle\DependencyInjection\AbstractContainerAware;
use WellCommerce\Bundle\OrderBundle\Entity\Order;
use WellCommerce\Bundle\OrderBundle\Provider\OrderModifierProviderInterface;

/**
 * Class OrderClientDiscountVisitor
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class OrderClientGroupDiscountVisitor extends AbstractContainerAware implements OrderVisitorInterface
{
    /**
     * @var OrderModifierProviderInterface
     */
    private $orderModifierProvider;
    
    /**
     * @var CurrencyHelperInterface
     */
    private $currencyHelper;
    
    /**
     * OrderClientDiscountVisitor constructor.
     *
     * @param OrderModifierProviderInterface $orderModifierProvider
     * @param CurrencyHelperInterface        $currencyHelper
     */
    public function __construct(OrderModifierProviderInterface $orderModifierProvider, CurrencyHelperInterface $currencyHelper)
    {
        $this->orderModifierProvider = $orderModifierProvider;
        $this->currencyHelper        = $currencyHelper;
    }
    
    public function visitOrder(Order $order)
    {
        $modifierValue = 0;
        
        if (null === $order->getCoupon()) {
            $discount      = $this->evaluateDiscount($order);
            $modifierValue = round($discount / 100, 2);
        }
        
        if ($modifierValue > 0) {
            $modifier = $this->orderModifierProvider->getOrderModifier($order, 'client_group_discount');
            $modifier->setCurrency($order->getCurrency());
            $modifier->setGrossAmount($order->getProductTotal()->getGrossPrice() * $modifierValue);
            $modifier->setNetAmount($order->getProductTotal()->getNetPrice() * $modifierValue);
            $modifier->setTaxAmount($order->getProductTotal()->getTaxAmount() * $modifierValue);
            
        } else {
            $order->removeModifier('client_group_discount');
        }
    }
    
    private function evaluateDiscount(Order $order): float
    {
        $client = $order->getClient();
        
        if ($client instanceof Client) {
            $clientGroup = $client->getClientGroup();
            $language    = new ExpressionLanguage();
            
            try {
                $expression = $clientGroup->getDiscount();
                
                return (float)$language->evaluate($expression, [
                    'order' => $order,
                ]);
            } catch (\Exception $exception) {
                return 0;
            }
            
        }
        
        return 0;
    }
}
