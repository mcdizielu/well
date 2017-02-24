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

use WellCommerce\Bundle\AppBundle\Entity\ClientGroup;
use WellCommerce\Bundle\AppBundle\Entity\Company;
use WellCommerce\Bundle\AppBundle\Entity\Shop;
use WellCommerce\Bundle\AppBundle\Entity\Theme;
use WellCommerce\Bundle\CoreBundle\Entity\MailerConfiguration;
use WellCommerce\Bundle\CoreBundle\Test\Entity\AbstractEntityTestCase;

/**
 * Class ShopTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ShopTest extends AbstractEntityTestCase
{
    protected function createEntity()
    {
        return new Shop();
    }
    
    public function providerTestAccessor()
    {
        $faker = $this->getFakerGenerator();
        
        return [
            ['name', $faker->randomAscii],
            ['defaultCountry', $faker->countryCode],
            ['defaultCurrency', $faker->currencyCode],
            ['company', new Company()],
            ['mailerConfiguration', new MailerConfiguration()],
            ['theme', new Theme()],
            ['theme', null],
            ['clientGroup', new ClientGroup()],
            ['clientGroup', null],
            ['createdAt', $faker->dateTime],
            ['updatedAt', $faker->dateTime],
        ];
    }
}
