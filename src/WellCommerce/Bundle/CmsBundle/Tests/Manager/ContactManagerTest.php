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

namespace WellCommerce\Bundle\CmsBundle\Tests\Manager;

use WellCommerce\Bundle\CmsBundle\Entity\Contact;
use WellCommerce\Bundle\CoreBundle\Test\Manager\AbstractManagerTestCase;
use WellCommerce\Bundle\CoreBundle\Manager\ManagerInterface;

/**
 * Class ContactManagerTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ContactManagerTest extends AbstractManagerTestCase
{
    protected function get(): ManagerInterface
    {
        return $this->container->get('contact.manager');
    }
    
    protected function getExpectedEntityInterface(): string
    {
        return Contact::class;
    }
}
