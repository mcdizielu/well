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

namespace WellCommerce\Bundle\CouponBundle\Checker;

use WellCommerce\Bundle\AppBundle\Helper\CurrencyHelperInterface;
use WellCommerce\Bundle\CouponBundle\Entity\Coupon;
use WellCommerce\Bundle\OrderBundle\Entity\OrderProduct;
use WellCommerce\Bundle\OrderBundle\Provider\Front\OrderProviderInterface;
use WellCommerce\Bundle\OrderBundle\Repository\OrderRepositoryInterface;

/**
 * Class CouponChecker
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class CouponChecker implements CouponCheckerInterface
{
    /**
     * @var string
     */
    private $error = '';
    
    /**
     * @var OrderProviderInterface
     */
    private $orderProvider;
    
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;
    
    /**
     * @var CurrencyHelperInterface
     */
    private $currencyHelper;
    
    /**
     * CouponChecker constructor.
     *
     * @param OrderProviderInterface   $orderProvider
     * @param OrderRepositoryInterface $orderRepository
     * @param CurrencyHelperInterface  $currencyHelper
     */
    public function __construct(
        OrderProviderInterface $orderProvider,
        OrderRepositoryInterface $orderRepository,
        CurrencyHelperInterface $currencyHelper
    ) {
        $this->orderProvider   = $orderProvider;
        $this->orderRepository = $orderRepository;
        $this->currencyHelper  = $currencyHelper;
    }
    
    public function isValid(Coupon $coupon = null): bool
    {
        if (null === $coupon) {
            $this->error = 'coupon.error.not_found';
            
            return false;
        }
        
        if (false === $this->isStartDateValid($coupon)) {
            $this->error = 'coupon.error.future_coupon';
            
            return false;
        }
        
        if (false === $this->isNotExpired($coupon)) {
            $this->error = 'coupon.error.coupon_expired';
            
            return false;
        }
        
        if (false === $this->hasMinimumOrderValue($coupon)) {
            $this->error = 'coupon.error.minimum_order_value';
            
            return false;
        }
        
        if ($coupon->isExcludePromotions() && true === $this->hasOnlyPromotionProducts()) {
            $this->error = 'coupon.error.only_promotion_products';
            
            return false;
        }
        
        return true;
    }
    
    public function getError(): string
    {
        return $this->error;
    }
    
    public function isStartDateValid(Coupon $coupon): bool
    {
        $now             = new \DateTime();
        $couponStartDate = $coupon->getValidFrom();
        
        if ($couponStartDate instanceof \DateTime) {
            return $now >= $couponStartDate;
        }
        
        return true;
    }
    
    public function isNotExpired(Coupon $coupon): bool
    {
        $now           = new \DateTime();
        $couponEndDate = $coupon->getValidTo();
        
        if ($couponEndDate instanceof \DateTime) {
            return $now <= $couponEndDate;
        }
        
        return true;
    }
    
    private function hasMinimumOrderValue(Coupon $coupon): bool
    {
        $order         = $this->orderProvider->getCurrentOrder();
        $productsValue = $order->getProductTotal()->getGrossPrice();
        $value         = $this->currencyHelper->convert($productsValue, $order->getCurrency(), $coupon->getCurrency());
        
        return $value >= $coupon->getMinimumOrderValue();
    }
    
    private function hasOnlyPromotionProducts(): bool
    {
        $hasOnlyPromotionProducts = true;
        $order                    = $this->orderProvider->getCurrentOrder();
        
        $order->getProducts()->map(function (OrderProduct $orderProduct) use (&$hasOnlyPromotionProducts) {
            if (false === $orderProduct->getProduct()->getSellPrice()->isDiscountValid()) {
                $hasOnlyPromotionProducts = false;
            }
        });
        
        return $hasOnlyPromotionProducts;
    }
}
