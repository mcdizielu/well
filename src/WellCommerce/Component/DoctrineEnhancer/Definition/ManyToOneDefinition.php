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
 * Class ManyToOneDefinition
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class ManyToOneDefinition extends AbstractMappingDefinition
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'fieldName',
            'inversedBy',
            'cascade',
            'fetch',
            'joinColumns',
            'targetEntity',
        ]);
        
        $resolver->setDefaults([
            'inversedBy' => null,
            'fetch'      => ClassMetadataInfo::FETCH_EXTRA_LAZY,
            'cascade'    => [
                'remove',
                'persist',
                'refresh',
                'merge',
                'detach',
            ],
        ]);
        
        $resolver->setAllowedTypes('fieldName', 'string');
        $resolver->setAllowedTypes('targetEntity', 'string');
        $resolver->setAllowedTypes('fetch', 'integer');
        $resolver->setAllowedTypes('cascade', 'array');
        $resolver->setAllowedTypes('inversedBy', ['string', 'null']);
        $resolver->setAllowedTypes('joinColumns', 'array');
    }
    
    public function getClassMetadataMethod(): string
    {
        return MappingDefinitionInterface::CLASS_METADATA_METHOD_MANY_TO_ONE;
    }
}
