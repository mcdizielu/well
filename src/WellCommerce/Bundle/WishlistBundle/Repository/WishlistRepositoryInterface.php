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

namespace WellCommerce\Bundle\WishlistBundle\Repository;

use Doctrine\Common\Collections\Collection;
use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\CoreBundle\Doctrine\Repository\RepositoryInterface;

/**
 * Interface WishlistRepositoryInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface WishlistRepositoryInterface extends RepositoryInterface
{
    public function getClientWishlistCollection(Client $client): Collection;
}
