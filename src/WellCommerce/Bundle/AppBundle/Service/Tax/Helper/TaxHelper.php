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

namespace WellCommerce\Bundle\AppBundle\Service\Tax\Helper;

/**
 * Class TaxHelper
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class TaxHelper implements TaxHelperInterface
{
    public function calculateNetPrice(float $grossPrice, float $taxRate): float
    {
        return round($grossPrice / (1 + $taxRate / 100), 2);
    }
}
