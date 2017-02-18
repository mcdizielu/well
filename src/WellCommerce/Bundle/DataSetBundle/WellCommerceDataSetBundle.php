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

namespace WellCommerce\Bundle\DataSetBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use WellCommerce\Bundle\DataSetBundle\DependencyInjection\Compiler\DataSetContextPass;
use WellCommerce\Bundle\DataSetBundle\DependencyInjection\Compiler\DataSetTransformerPass;

/**
 * Class WellCommerceDataSetBundle
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class WellCommerceDataSetBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new DataSetContextPass());
        $container->addCompilerPass(new DataSetTransformerPass());
    }
}
