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

namespace WellCommerce\Bundle\OAuthBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class RegisterOAuthProviderPass
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class RegisterOAuthProviderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $tag        = 'oauth.provider';
        $definition = $container->getDefinition('oauth.provider.collection');
        
        foreach ($container->findTaggedServiceIds($tag) as $id => $attributes) {
            $definition->addMethodCall('set', [
                $attributes[0]['alias'],
                new Reference($id),
            ]);
        }
    }
}
