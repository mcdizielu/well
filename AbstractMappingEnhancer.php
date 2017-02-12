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

namespace WellCommerce\Component\DoctrineEnhancer;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use WellCommerce\Component\DoctrineEnhancer\Definition\MappingDefinitionCollection;
use WellCommerce\Component\DoctrineEnhancer\Definition\MappingDefinitionInterface;
use WellCommerce\Component\DoctrineEnhancer\TraitGenerator\TraitGenerator;

/**
 * Class AbstractMappingEnhancer
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
abstract class AbstractMappingEnhancer implements MappingEnhancerInterface
{
    public function visitClassMetadata(ClassMetadataInfo $metadata)
    {
        $collection = $this->getMappingDefinitionCollection();
        $this->extendClassMetadata($metadata, $collection);
    }
    
    public function getMappingDefinitionCollection(): MappingDefinitionCollection
    {
        $collection = new MappingDefinitionCollection();
        $this->configureMappingDefinition($collection);
        
        return $collection;
    }
    
    abstract protected function configureMappingDefinition(MappingDefinitionCollection $collection);
    
    private function extendClassMetadata(ClassMetadataInfo $metadata, MappingDefinitionCollection $collection)
    {
        $collection->forAll(function (MappingDefinitionInterface $definition) use ($metadata) {
            $reflectionClass = $metadata->getReflectionClass();
            if (true === $reflectionClass->hasProperty($definition->getPropertyName())) {
                $metadata->{$definition->getClassMetadataMethod()}($definition->getOptions());
            }
        });
    }
}
