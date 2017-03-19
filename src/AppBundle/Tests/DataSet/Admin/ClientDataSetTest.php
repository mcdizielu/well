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

namespace WellCommerce\Bundle\AppBundle\Tests\DataSet\Admin;

use WellCommerce\Bundle\CoreBundle\Test\DataSet\AbstractDataSetTestCase;

/**
 * Class ClientDataSetTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ClientDataSetTest extends AbstractDataSetTestCase
{
    protected function get()
    {
        return $this->container->get('client.dataset.admin');
    }
    
    protected function getColumns()
    {
        return [
            'id'          => 'client.id',
            'firstName'   => 'client.contactDetails.firstName',
            'lastName'    => 'client.contactDetails.lastName',
            'companyName' => 'client.billingAddress.companyName',
            'vatId'       => 'client.billingAddress.vatId',
            'email'       => 'client.contactDetails.email',
            'phone'       => 'client.contactDetails.phone',
            'groupId'     => 'client_group.id',
            'groupName'   => 'client_group_translation.name',
            'createdAt'   => 'client.createdAt',
            'shop'        => 'IDENTITY(client.shop)',
            'lastActive'  => 'client_cart.updatedAt',
            'cart'        => 'IF_NULL(client_cart.id, 0)',
            'cartValue'   => 'IF_NULL(client_cart.summary.grossAmount, 0)',
        ];
    }
}
