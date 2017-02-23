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

namespace WellCommerce\Component\Serializer\Metadata\Factory;

use WellCommerce\Component\Serializer\Metadata\SerializationClassMetadata;

/**
 * Class SerializationClassMetadataFactory
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class SerializationClassMetadataFactory
{
    /**
     * Creates a metadata object for given class and parameters
     *
     * @param string $class
     * @param array  $parameters
     *
     * @return SerializationClassMetadata
     */
    public function create(string $class, array $parameters): SerializationClassMetadata
    {
        return new SerializationClassMetadata($class, $parameters);
    }
}
