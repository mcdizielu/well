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

namespace WellCommerce\Component\DoctrineEnhancer\Definition;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EmbeddableDefinition
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class EmbeddableDefinition extends AbstractMappingDefinition
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'fieldName',
            'class',
            'columnPrefix',
        ]);
        
        $resolver->setAllowedTypes('fieldName', 'string');
        $resolver->setAllowedTypes('class', 'string');
        $resolver->setAllowedTypes('columnPrefix', ['string', 'null']);
    }
    
    public function getClassMetadataMethod(): string
    {
        return MappingDefinitionInterface::CLASS_METADATA_METHOD_EMBEDDABLE;
    }
}
