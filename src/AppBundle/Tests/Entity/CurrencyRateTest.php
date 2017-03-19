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

namespace WellCommerce\Bundle\AppBundle\Tests\Entity;

use WellCommerce\Bundle\AppBundle\Entity\CurrencyRate;
use WellCommerce\Bundle\CoreBundle\Test\Entity\AbstractEntityTestCase;

/**
 * Class CurrencyRateTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class CurrencyRateTest extends AbstractEntityTestCase
{
    protected function createEntity()
    {
        return new CurrencyRate();
    }
    
    public function providerTestAccessor()
    {
        $faker = $this->getFakerGenerator();
        
        return [
            ['currencyFrom', $faker->currencyCode],
            ['currencyTo', $faker->currencyCode],
            ['exchangeRate', $faker->randomFloat(4)],
            ['createdAt', $faker->dateTime],
            ['updatedAt', $faker->dateTime],
        ];
    }
}
