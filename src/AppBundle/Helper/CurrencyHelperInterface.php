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

namespace WellCommerce\Bundle\AppBundle\Helper;

/**
 * Interface CurrencyHelperInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface CurrencyHelperInterface
{
    public function format(float $amount, string $currency = null, string $locale = null): string;
    
    public function convert(float $amount, string $baseCurrency = null, string $targetCurrency = null, int $quantity = 1): float;
    
    public function getCurrencyRate(string $baseCurrency = null, string $targetCurrency = null): float;
    
    public function convertAndFormat(
        float $amount,
        string $baseCurrency = null,
        string $targetCurrency = null,
        int $quantity = 1,
        string $locale = null
    ): string;
}
