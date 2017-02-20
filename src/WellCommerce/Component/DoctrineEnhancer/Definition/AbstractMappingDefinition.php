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
 * Class AbstractMappingDefinition
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
abstract class AbstractMappingDefinition implements MappingDefinitionInterface
{
    /**
     * @var array
     */
    protected $options = [];
    
    public function __construct(array $options)
    {
        $optionsResolver = new OptionsResolver();
        $this->configureOptions($optionsResolver);
        $this->options = $optionsResolver->resolve($options);
    }
    
    public function getOptions(): array
    {
        return $this->options;
    }
    
    public function getPropertyName(): string
    {
        return $this->options['fieldName'];
    }
}
