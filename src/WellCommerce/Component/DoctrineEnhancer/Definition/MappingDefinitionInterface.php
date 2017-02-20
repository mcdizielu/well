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
 * Interface MappingDefinitionInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface MappingDefinitionInterface
{
    const CLASS_METADATA_METHOD_FIELD        = 'mapField';
    const CLASS_METADATA_METHOD_MANY_TO_MANY = 'mapManyToMany';
    const CLASS_METADATA_METHOD_MANY_TO_ONE  = 'mapManyToOne';
    const CLASS_METADATA_METHOD_ONE_TO_MANY  = 'mapOneToMany';
    
    public function configureOptions(OptionsResolver $resolver);
    
    public function getOptions(): array;
    
    public function getClassMetadataMethod(): string;
    
    public function getPropertyName(): string;
}
