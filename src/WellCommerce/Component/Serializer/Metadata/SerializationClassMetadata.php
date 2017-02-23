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

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WellCommerce\Component\Serializer\Metadata\Collection;
use WellCommerce\Component\Serializer\Metadata\Factory\AssociationMetadataFactory;
use WellCommerce\Component\Serializer\Metadata\Factory\FieldMetadataFactory;

/**
 * Class ClassMetadata
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class SerializationClassMetadata implements SerializationClassMetadataInterface
{
    /**
     * @var string
     */
    protected $class;
    
    /**
     * @var array
     */
    protected $options = [];
    
    public function __construct(string $class, array $options = [])
    {
        $this->class = $class;
        $resolver    = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }
    
    public function getClass(): string
    {
        return $this->class;
    }
    
    public function getFields(): Collection\FieldMetadataCollection
    {
        return $this->options['fields'];
    }
    
    public function getAssociations(): Collection\AssociationMetadataCollection
    {
        return $this->options['associations'];
    }
    
    public function getCallbacks(): array
    {
        return $this->options['callbacks'];
    }
    
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'fields',
            'associations',
            'callbacks',
        ]);
        
        $fieldsNormalizer = function (Options $options, $fields) {
            $collection = new Collection\FieldMetadataCollection();
            $factory    = new FieldMetadataFactory();
            
            foreach ($fields as $fieldName => $parameters) {
                $fieldMetadata = $factory->create($fieldName, $parameters);
                $collection->add($fieldMetadata);
            }
            
            return $collection;
        };
        
        $associationsNormalizer = function (Options $options, $associations) {
            $collection = new Collection\AssociationMetadataCollection();
            $factory    = new AssociationMetadataFactory();
            
            foreach ($associations as $associationName => $parameters) {
                $associationMetadata = $factory->create($associationName, $parameters);
                $collection->add($associationMetadata);
            }
            
            return $collection;
        };
        
        $resolver->setNormalizer('fields', $fieldsNormalizer);
        $resolver->setNormalizer('associations', $associationsNormalizer);
        
        $resolver->setDefaults([
            'fields'       => new Collection\FieldMetadataCollection(),
            'associations' => new Collection\AssociationMetadataCollection(),
            'callbacks'    => [],
        ]);
        
        $resolver->setAllowedTypes('fields', ['array', Collection\FieldMetadataCollection::class]);
        $resolver->setAllowedTypes('associations', ['array', Collection\AssociationMetadataCollection::class]);
        $resolver->setAllowedTypes('callbacks', 'array');
    }
}
