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

namespace WellCommerce\Bundle\AppBundle\Repository;

use WellCommerce\Bundle\AppBundle\Entity\Shop;
use WellCommerce\Bundle\CoreBundle\Doctrine\Repository\RepositoryInterface;

/**
 * Interface ShopRepositoryInterface
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
interface ShopRepositoryInterface extends RepositoryInterface
{
    public function resolve(int $currentShopId, string $host): Shop;
}
