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

namespace WellCommerce\Bundle\AppBundle\Console\Action;

use WellCommerce\Bundle\CoreBundle\Console\Action\ConsoleActionInterface;

/**
 * Class LoadSystemDefaultsAction
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class LoadSystemDefaultsAction implements ConsoleActionInterface
{
    public function getCommandsToExecute()
    {
        return [
            'wellcommerce:system:load-defaults' => [],
        ];
    }
}
