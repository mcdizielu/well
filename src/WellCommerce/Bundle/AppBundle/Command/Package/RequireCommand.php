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

namespace WellCommerce\Bundle\AppBundle\Command\Package;

use WellCommerce\Bundle\CoreBundle\Helper\Package\PackageHelperInterface;

/**
 * Class RequireCommand
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class RequireCommand extends AbstractPackageCommand
{
    protected $composerOperation = PackageHelperInterface::ACTION_REQUIRE;
    
    protected function configure()
    {
        parent::configure();
        $this->setDescription('Install WellCommerce package');
        $this->setName('wellcommerce:package:require');
    }
}

