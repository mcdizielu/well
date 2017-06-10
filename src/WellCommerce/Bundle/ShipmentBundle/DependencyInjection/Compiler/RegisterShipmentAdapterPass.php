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

namespace WellCommerce\Bundle\ShipmentBundle\DependencyInjection\Compiler;

use WellCommerce\Bundle\CoreBundle\DependencyInjection\Compiler\AbstractCollectionPass;

/**
 * Class RegisterShipmentAdapterPass
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class RegisterShipmentAdapterPass extends AbstractCollectionPass
{
    /**
     * @var string
     */
    protected $collectionServiceId = 'shipment.adapter.collection';

    /**
     * @var string
     */
    protected $serviceTag = 'shipment.adapter';
}
