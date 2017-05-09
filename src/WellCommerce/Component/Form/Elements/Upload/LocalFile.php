<?php

namespace WellCommerce\Component\Form\Elements\Upload;

use Symfony\Component\OptionsResolver\OptionsResolver;
use WellCommerce\Component\Form\Elements\Attribute;
use WellCommerce\Component\Form\Elements\AttributeCollection;

/**
 * Class LocalFile
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class LocalFile extends File
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        
        $resolver->setRequired([
            'file_source',
        ]);
        
        $resolver->setDefaults([
            'file_types'   => ['jpg', 'jpeg', 'png', 'gif', 'csv', 'xml', 'txt', 'pdf'],
            'load_route'   => 'admin.local_file.index',
            'upload_route' => 'admin.local_file.upload',
        ]);
        
        $resolver->setAllowedTypes('file_source', 'string');
    }
    
    /**
     * {@inheritdoc}
     */
    public function prepareAttributesCollection(AttributeCollection $collection)
    {
        parent::prepareAttributesCollection($collection);
        $collection->add(new Attribute('oTypeIcons', $this->getTypeIcons(), Attribute::TYPE_ARRAY));
        $collection->add(new Attribute('sFilePath', $this->getOption('file_source')));
    }
}
