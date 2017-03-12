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

namespace WellCommerce\Bundle\FeatureBundle\Form\Elements;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WellCommerce\Component\Form\Elements\AbstractField;
use WellCommerce\Component\Form\Elements\Attribute;
use WellCommerce\Component\Form\Elements\AttributeCollection;
use WellCommerce\Component\Form\Elements\ElementInterface;

/**
 * Class FeatureEditor
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class FeatureEditor extends AbstractField implements ElementInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        
        $resolver->setRequired([
            'get_sets_route',
            'get_groups_route',
            'product_id',
            'feature_set_field',
        ]);
        
        $resolver->setDefaults([
            'product_id'       => 0,
            'get_sets_route'   => 'admin.feature_set.ajax.index',
            'get_groups_route' => 'admin.feature_group.ajax.index',
        ]);
        
        $resolver->setAllowedTypes('feature_set_field', ElementInterface::class);
        
        $fieldNormalizer = function (Options $options, $value) {
            if (!$value instanceof ElementInterface) {
                throw new \InvalidArgumentException('Passed field should implement "ElementInterface" and have accessible "getName" method.');
            }
            
            return $value->getName();
        };
        
        $resolver->setNormalizer('feature_set_field', $fieldNormalizer);
    }
    
    public function prepareAttributesCollection(AttributeCollection $collection)
    {
        parent::prepareAttributesCollection($collection);
        $collection->add(new Attribute('sGetSetsRoute', $this->getOption('get_sets_route')));
        $collection->add(new Attribute('sGetGroupsRoute', $this->getOption('get_groups_route')));
        $collection->add(new Attribute('sFeatureSetField', $this->getOption('feature_set_field')));
        $collection->add(new Attribute('sProductId', $this->getOption('product_id')));
    }
}
