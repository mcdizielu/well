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

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class OneToManyDefinition
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class OneToManyDefinition extends AbstractMappingDefinition
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'fieldName',
            'targetEntity',
            'mappedBy',
            'orphanRemoval',
            'fetch',
            'cascade',
        ]);
        
        $resolver->setDefaults([
            'mappedBy'      => null,
            'orphanRemoval' => true,
            'fetch'         => ClassMetadataInfo::FETCH_EXTRA_LAZY,
            'cascade'       => [
                'remove',
                'persist',
                'refresh',
                'merge',
                'detach',
            ],
        ]);
        
        $resolver->setAllowedTypes('fieldName', 'string');
        $resolver->setAllowedTypes('targetEntity', 'string');
        $resolver->setAllowedTypes('mappedBy', ['string', 'null']);
        $resolver->setAllowedTypes('orphanRemoval', 'bool');
        $resolver->setAllowedTypes('fetch', 'integer');
        $resolver->setAllowedTypes('cascade', 'array');
    }
    
    public function getClassMetadataMethod(): string
    {
        return MappingDefinitionInterface::CLASS_METADATA_METHOD_ONE_TO_MANY;
    }
}
