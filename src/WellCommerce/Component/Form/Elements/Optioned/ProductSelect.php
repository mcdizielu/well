<?php

namespace WellCommerce\Component\Form\Elements\Optioned;

use Symfony\Component\OptionsResolver\OptionsResolver;
use WellCommerce\Component\Form\Elements\Attribute;
use WellCommerce\Component\Form\Elements\AttributeCollection;

/**
 * Class DataGridSelect
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ProductSelect extends Select
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        
        $resolver->setRequired([
            'load_route',
            'repeat_min',
            'repeat_max',
        ]);
        
        $resolver->setDefaults([
            'load_route' => 'admin.product.grid',
        ]);
        
        $resolver->setAllowedTypes('repeat_min', 'numeric');
        $resolver->setAllowedTypes('repeat_max', 'numeric');
        $resolver->setAllowedTypes('load_route', 'string');
    }
    
    public function prepareAttributesCollection(AttributeCollection $collection)
    {
        parent::prepareAttributesCollection($collection);
        $collection->add(new Attribute('sLoadRoute', $this->getOption('load_route')));
        $collection->add(new Attribute('oRepeat', $this->prepareRepetitions(), Attribute::TYPE_ARRAY));
    }
}
