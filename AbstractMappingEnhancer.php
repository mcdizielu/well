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
use Wingu\OctopusCore\CodeGenerator\CodeLineGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\MethodGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\Modifiers;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\PropertyGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\TraitGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator;

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
    
    public function visitTraitGenerator(TraitGenerator $generator)
    {
        $collection = $this->getMappingDefinitionCollection();
        $this->extendTrait($generator, $collection);
    }
    
    public function supportsEntity(string $className): bool
    {
        return $className === $this->getSupportedEntityClass();
    }
    
    public function supportsEntityExtraTrait(string $className): bool
    {
        return $className === $this->getSupportedEntityExtraTraitClass();
    }
    
    abstract protected function configureMappingDefinition(MappingDefinitionCollection $collection);
    
    protected function getMappingDefinitionCollection(): MappingDefinitionCollection
    {
        $collection = new MappingDefinitionCollection();
        $this->configureMappingDefinition($collection);
        
        return $collection;
    }
    
    private function extendClassMetadata(ClassMetadataInfo $metadata, MappingDefinitionCollection $collection)
    {
        $collection->forAll(function (MappingDefinitionInterface $definition) use ($metadata) {
            $reflectionClass = $metadata->getReflectionClass();
            if (true === $reflectionClass->hasProperty($definition->getPropertyName())) {
                $metadata->{$definition->getClassMetadataMethod()}($definition->getOptions());
            }
        });
    }
    
    private function extendTrait(TraitGenerator $generator, MappingDefinitionCollection $collection)
    {
        $collection->forAll(function (MappingDefinitionInterface $definition) use ($generator) {
            $this->addProperty($generator, $definition->getPropertyName());
            $this->addGetterMethod($generator, $definition->getPropertyName());
            $this->addSetterMethod($generator, $definition->getPropertyName());
        });
    }
    
    private function addProperty(TraitGenerator $generator, string $property)
    {
        $generator->addProperty(new PropertyGenerator($property, null, Modifiers::MODIFIER_PROTECTED));
    }
    
    private function addGetterMethod(TraitGenerator $generator, string $property)
    {
        $getterMethodName = 'get' . $this->convertToStudlyCase($property);
        $variableName     = strval($property);
        $method           = new MethodGenerator($getterMethodName);
        $method->addBodyLine(new CodeLineGenerator('return $this->' . $variableName . ';'));
        $method->setVisibility(Modifiers::VISIBILITY_PUBLIC);
        $generator->addMethod($method);
    }
    
    private function addSetterMethod(TraitGenerator $generator, string $property)
    {
        $setterMethodName = 'set' . $this->convertToStudlyCase($property);
        $variableName     = strval($property);
        $method           = new MethodGenerator($setterMethodName);
        $method->addBodyLine(new CodeLineGenerator('$this->' . $variableName . ' = $' . $variableName . ';'));
        $method->addParameter(new ParameterGenerator($variableName));
        $method->setVisibility(Modifiers::VISIBILITY_PUBLIC);
        $generator->addMethod($method);
    }
    
    private function convertToStudlyCase(string $value): string
    {
        $value = ucwords(str_replace(['-', '_'], ' ', $value));
        
        return str_replace(' ', '', $value);
    }
}
