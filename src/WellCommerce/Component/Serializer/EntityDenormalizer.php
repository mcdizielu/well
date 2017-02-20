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

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use WellCommerce\Component\Serializer\Metadata\Collection\AssociationMetadataCollection;
use WellCommerce\Component\Serializer\Metadata\Collection\FieldMetadataCollection;

/**
 * Class EntityDenormalizer
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class EntityDenormalizer extends AbstractSerializer implements DenormalizerInterface
{
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $resource               = $context['resource'];
        $serializationMetadata  = $this->getSerializationMetadata($resource);
        $serializedFields       = $serializationMetadata->getFields();
        $serializedAssociations = $serializationMetadata->getAssociations();
        
        $this->updateEntityFields($data, $serializedFields, $resource);
        $this->updateEntityAssociations($data, $serializedAssociations, $resource);
        
        return $resource;
    }
    
    /**
     * Updates the resource fields with given data
     *
     * @param array                   $properties
     * @param FieldMetadataCollection $collection
     * @param object                  $resource
     */
    protected function updateEntityFields(array $properties, FieldMetadataCollection $collection, $resource)
    {
        foreach ($properties as $propertyName => $propertyValue) {
            if ($collection->has($propertyName) && is_scalar($propertyValue)) {
                if ($this->propertyAccessor->isWritable($resource, $propertyName)) {
                    $this->propertyAccessor->setValue($resource, $propertyName, $propertyValue);
                }
            }
        }
    }
    
    /**
     * Loop through all passed properties and update the associations
     *
     * @param array                         $properties
     * @param AssociationMetadataCollection $collection
     * @param object                        $resource
     */
    protected function updateEntityAssociations(array $properties, AssociationMetadataCollection $collection, $resource)
    {
        $entityMetadata = $this->getEntityMetadata($resource);
        
        foreach ($properties as $propertyName => $propertyValue) {
            if ($collection->has($propertyName)) {
                $this->updateEntityAssociation($propertyName, $propertyValue, $resource, $entityMetadata);
            }
        }
    }
    
    /**
     * Updates a single entity association
     *
     * @param string $propertyName
     * @param array  $propertyValue
     * @param object $resource
     */
    protected function updateEntityAssociation(string $propertyName, array $propertyValue, $resource, ClassMetadata $entityMetadata)
    {
        if ($entityMetadata->isSingleValuedAssociation($propertyName)) {
            $associationTargetClass = $entityMetadata->getAssociationTargetClass($propertyName);
            $repository             = $this->getRepositoryByTargetClass($associationTargetClass);
            $associationResource    = $repository->findOneBy($propertyValue);
            if (null !== $associationResource && $this->propertyAccessor->isWritable($resource, $propertyName)) {
                $this->propertyAccessor->setValue($resource, $propertyName, $associationResource);
            }
        }
    }
    
    protected function getRepositoryByTargetClass(string $class): ObjectRepository
    {
        $manager = $this->managerRegistry->getManagerForClass($class);
        
        return $manager->getRepository($class);
    }
    
    public function supportsDenormalization($data, $type, $format = null)
    {
        $class   = $this->getRealClass($data);
        $manager = $this->getEntityManager($class);
        $factory = $manager->getMetadataFactory();
        
        return is_array($data) && $factory->hasMetadataFor($type);
    }
}
