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

namespace WellCommerce\Bundle\SearchBundle\Console\Action;

use WellCommerce\Bundle\CoreBundle\Console\Action\ConsoleActionInterface;

/**
 * Class ReindexAction
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ReindexAction implements ConsoleActionInterface
{
    public function getCommandsToExecute()
    {
        return [
            'wellcommerce:search:reindex' => [
                '--batch' => 150,
            ],
        ];
    }
}
