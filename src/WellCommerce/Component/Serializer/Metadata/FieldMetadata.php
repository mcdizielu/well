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

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FieldMetadata
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class FieldMetadata implements FieldMetadataInterface
{
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var array
     */
    protected $options;
    
    public function __construct(string $name, array $options)
    {
        $this->name = $name;
        $resolver   = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function getGroups(): array
    {
        return $this->options['groups'];
    }
    
    public function hasGroup(string $group): bool
    {
        return in_array($group, $this->getGroups());
    }
    
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'groups',
        ]);
        
        $resolver->setDefaults([
            'groups' => [],
        ]);
        
        $resolver->setAllowedTypes('groups', 'array');
    }
}
