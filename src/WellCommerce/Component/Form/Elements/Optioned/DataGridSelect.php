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
class DataGridSelect extends Select
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        
        $resolver->setRequired([
            'key',
            'columns',
            'selected_columns',
            'load_route',
            'repeat_min',
            'repeat_max',
        ]);
        
        $resolver->setDefaults([
            'key'              => 'id',
            'columns'          => [],
            'selected_columns' => [],
        ]);
        
        $resolver->setAllowedTypes('repeat_min', 'numeric');
        $resolver->setAllowedTypes('repeat_max', 'numeric');
        $resolver->setAllowedTypes('key', 'string');
        $resolver->setAllowedTypes('columns', 'array');
        $resolver->setAllowedTypes('selected_columns', 'array');
        $resolver->setAllowedTypes('load_route', 'string');
    }
    
    public function prepareAttributesCollection(AttributeCollection $collection)
    {
        parent::prepareAttributesCollection($collection);
        $collection->add(new Attribute('sKey', $this->getOption('key')));
        $collection->add(new Attribute('sLoadRoute', $this->getOption('load_route')));
        $collection->add(new Attribute('aoColumns', $this->getOption('columns'), Attribute::TYPE_ARRAY));
        $collection->add(new Attribute('aoSelectedColumns', $this->getOption('selected_columns'), Attribute::TYPE_ARRAY));
        $collection->add(new Attribute('oRepeat', $this->prepareRepetitions(), Attribute::TYPE_ARRAY));
    }
}
