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

namespace WellCommerce\Bundle\AppBundle\Tests\Manager;

use WellCommerce\Bundle\AppBundle\Entity\Currency;
use WellCommerce\Bundle\CoreBundle\Manager\ManagerInterface;
use WellCommerce\Bundle\CoreBundle\Test\Manager\AbstractManagerTestCase;

/**
 * Class CurrencyManagerTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class CurrencyManagerTest extends AbstractManagerTestCase
{
    protected function get(): ManagerInterface
    {
        return $this->container->get('currency.manager');
    }
    
    protected function getExpectedEntityInterface(): string
    {
        return Currency::class;
    }
}
