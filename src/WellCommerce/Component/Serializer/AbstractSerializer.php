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

namespace WellCommerce\Component\Serializer;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\Common\Util\Inflector;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;
use WellCommerce\Component\Serializer\Metadata\Collection\SerializationMetadataCollection;
use WellCommerce\Component\Serializer\Metadata\Loader\SerializationMetadataLoaderInterface;
use WellCommerce\Component\Serializer\Metadata\SerializationClassMetadataInterface;

/**
 * Class AbstractSerializer
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
abstract class AbstractSerializer implements SerializerAwareInterface
{
    /**
     * @var ManagerRegistry
     */
    protected $managerRegistry;
    
    /**
     * @var \Symfony\Component\PropertyAccess\PropertyAccessor
     */
    protected $propertyAccessor;
    
    /**
     * @var Serializer
     */
    protected $serializer;
    
    /**
     * @var SerializationMetadataLoaderInterface
     */
    protected $serializationMetadataLoader;
    
    /**
     * @var \WellCommerce\Component\Serializer\Metadata\Collection\SerializationMetadataCollection
     */
    protected $serializationMetadataCollection;
    
    /**
     * @var array
     */
    protected $context = [];
    
    /**
     * @var string
     */
    protected $format;
    
    public function __construct(ManagerRegistry $managerRegistry, SerializationMetadataLoaderInterface $serializationMetadataLoader)
    {
        $this->managerRegistry                 = $managerRegistry;
        $this->serializationMetadataLoader     = $serializationMetadataLoader;
        $this->propertyAccessor                = PropertyAccess::createPropertyAccessor();
        $this->serializationMetadataCollection = $this->getSerializationMetadataCollection();
    }
    
    public function setSerializer(SerializerInterface $serializer)
    {
        if (!$serializer instanceof NormalizerInterface || !$serializer instanceof DenormalizerInterface) {
            throw new LogicException('Injected serializer must implement both NormalizerInterface and DenormalizerInterface');
        }
        
        $this->serializer = $serializer;
    }
    
    protected function getSerializationMetadataCollection(): SerializationMetadataCollection
    {
        return $this->serializationMetadataLoader->loadMetadata();
    }
    
    protected function getSerializationMetadata($entity): SerializationClassMetadataInterface
    {
        $className = $this->getRealClass($entity);
        
        return $this->serializationMetadataCollection->get($className);
    }
    
    protected function hasSerializationMetadata($entity): bool
    {
        $className = $this->getRealClass($entity);
        
        return $this->serializationMetadataCollection->has($className);
    }
    
    protected function getEntityMetadata($entity): ClassMetadata
    {
        $class   = $this->getRealClass($entity);
        $manager = $this->getEntityManager($class);
        
        return $manager->getClassMetadata($class);
    }
    
    /**
     * Builds property path in array-notation style from given attribute's name
     *
     * @param $attributeName
     *
     * @return PropertyPath
     */
    protected function getPropertyPath(string $attributeName): PropertyPath
    {
        $elements = explode('.', $attributeName);
        
        $wrapped = array_map(function ($element) {
            
            return "[{$element}]";
        }, $elements);
        
        return new PropertyPath(implode('', $wrapped));
    }
    
    /**
     * Builds a property path from string
     *
     * @param $propertyName
     *
     * @return PropertyPath
     */
    protected function buildPath(string $propertyName): PropertyPath
    {
        $elements = explode('.', $propertyName);
        $wrapped  = array_map(function ($element) {
            $name = Inflector::classify($element);
            
            return Inflector::camelize($name);
        }, $elements);
        
        return new PropertyPath(implode('.', $wrapped));
    }
    
    /**
     * Returns the entity fields
     *
     * @param ClassMetadata $metadata
     *
     * @return array
     */
    protected function getEntityFields(ClassMetadata $metadata): array
    {
        $entityFields = $metadata->getFieldNames();
        $fields       = [];
        foreach ($entityFields as $field) {
            if (false === strpos($field, '.')) {
                $fields[$field] = $field;
            }
        }
        
        return $fields;
    }
    
    /**
     * Returns the entity embeddable fields
     *
     * @param ClassMetadata $metadata
     *
     * @return array
     */
    protected function getEntityEmbeddables(ClassMetadata $metadata): array
    {
        $entityFields = $metadata->getFieldNames();
        $embeddables  = [];
        foreach ($entityFields as $embeddableField) {
            if (false !== strpos($embeddableField, '.')) {
                list($embeddablePropertyName,) = explode('.', $embeddableField);
                $embeddables[$embeddablePropertyName] = $embeddablePropertyName;
            }
        }
        
        return $embeddables;
    }
    
    /**
     * Returns the entity fields
     *
     * @param ClassMetadata $metadata
     *
     * @return array
     */
    protected function getEntityAssociations(ClassMetadata $metadata): array
    {
        $entityAssociations = $metadata->getAssociationNames();
        $associations       = [];
        foreach ($entityAssociations as $association) {
            $associations[$association] = $association;
        }
        
        return $associations;
    }
    
    protected function getRealClass($object): string
    {
        $className = get_class($object);
        
        return ClassUtils::getRealClass($className);
    }
    
    protected function getEntityManager(string $class = null): ObjectManager
    {
        if (null === $class) {
            return $this->managerRegistry->getManager();
        }
        
        return $this->managerRegistry->getManagerForClass($class);
    }
}
