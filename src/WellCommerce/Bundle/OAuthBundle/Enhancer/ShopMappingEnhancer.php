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

namespace WellCommerce\Bundle\OAuthBundle\Enhancer;

use WellCommerce\Bundle\AppBundle\Entity\Shop;
use WellCommerce\Component\DoctrineEnhancer\AbstractMappingEnhancer;
use WellCommerce\Component\DoctrineEnhancer\Definition\FieldDefinition;
use WellCommerce\Component\DoctrineEnhancer\Definition\MappingDefinitionCollection;
use WellCommerce\Extra\AppBundle\Entity\ShopExtraTrait;

/**
 * Class ShopMappingEnhancer
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class ShopMappingEnhancer extends AbstractMappingEnhancer
{
    protected function configureMappingDefinition(MappingDefinitionCollection $collection)
    {
        $collection->add(new FieldDefinition([
            'fieldName'  => 'facebookConnectEnabled',
            'type'       => 'boolean',
            'columnName' => 'facebook_enabled',
            'nullable'   => false,
            'options'    => [
                'default' => false,
            ],
        ]));

        $collection->add(new FieldDefinition([
            'fieldName'  => 'facebookConnectAppId',
            'type'       => 'string',
            'columnName' => 'facebook_connect_app_id',
        ]));

        $collection->add(new FieldDefinition([
            'fieldName'  => 'facebookConnectAppSecret',
            'type'       => 'string',
            'columnName' => 'facebook_connect_app_secret',
        ]));

        $collection->add(new FieldDefinition([
            'fieldName'  => 'googleConnectEnabled',
            'type'       => 'boolean',
            'columnName' => 'google_connect_enabled',
            'nullable'   => false,
            'options'    => [
                'default' => false,
            ],
        ]));

        $collection->add(new FieldDefinition([
            'fieldName'  => 'googleConnectAppId',
            'type'       => 'string',
            'columnName' => 'google_connect_app_id',
        ]));

        $collection->add(new FieldDefinition([
            'fieldName'  => 'googleConnectAppSecret',
            'type'       => 'string',
            'columnName' => 'google_connect_app_secret',
        ]));
    }
    
    public function getSupportedEntityClass(): string
    {
        return Shop::class;
    }
    
    public function getExtraTraitClass(): string
    {
        return ShopExtraTrait::class;
    }
}
