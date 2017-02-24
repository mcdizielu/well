<?php

namespace WellCommerce\Bundle\CouponBundle\Helper;

use WellCommerce\Bundle\CouponBundle\Entity\Coupon;

/**
 * Class CouponHelper
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class CouponHelper
{
    public static function formatModifier(Coupon $coupon): string
    {
        if ($coupon->getModifierType() === '%') {
            return sprintf('-%s%s', $coupon->getModifierValue(), $coupon->getModifierType());
        }
        
        return sprintf('-%s%s', $coupon->getModifierValue(), $coupon->getCurrency());
    }
}