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

namespace WellCommerce\Component\Serializer\Metadata;

/**
 * Interface SerializationClassMetadataInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface SerializationClassMetadataInterface
{
    public function getClass(): string;
    
    public function getFields(): Collection\FieldMetadataCollection;
    
    public function getAssociations(): Collection\AssociationMetadataCollection;
    
    public function getCallbacks(): array;
}
