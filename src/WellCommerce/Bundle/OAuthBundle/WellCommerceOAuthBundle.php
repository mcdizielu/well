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

namespace WellCommerce\Bundle\OAuthBundle;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use WellCommerce\Bundle\CoreBundle\HttpKernel\AbstractWellCommerceBundle;
use WellCommerce\Bundle\OAuthBundle\DependencyInjection\Compiler\RegisterOAuthProviderPass;

/**
 * Class WellCommerceOAuthBundle
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class WellCommerceOAuthBundle extends AbstractWellCommerceBundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new RegisterOAuthProviderPass());
    }

    public static function registerBundles(Collection $bundles, string $environment)
    {
        $bundles->add(new self());
    }
}
