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

namespace WellCommerce\Bundle\SearchBundle\Tests\DependencyInjection;

use WellCommerce\Bundle\CoreBundle\Test\DependencyInjection\AbstractExtensionTestCase;

/**
 * Class WishlistExtensionTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class WishlistExtensionTest extends AbstractExtensionTestCase
{
    public function getRequiredServices(): array
    {
        return [
            'services' => [
                [
                    'search.manager',
                ],
            ],
        ];
    }
}
