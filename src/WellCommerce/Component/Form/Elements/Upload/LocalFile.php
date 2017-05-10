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
            'file_types'          => ['jpg', 'jpeg', 'png', 'gif', 'csv', 'xml', 'txt', 'pdf'],
            'load_route'          => 'admin.local_file.index',
            'upload_route'        => 'admin.local_file.upload',
            'delete_route'        => 'admin.local_file.delete',
            'create_folder_route' => 'admin.local_file.create_folder',
            'max_upload_size'     => 2,
        ]);
        
        $resolver->setAllowedTypes('file_source', 'string');
        $resolver->setAllowedTypes('max_upload_size', 'integer');
    }
    
    /**
     * {@inheritdoc}
     */
    public function prepareAttributesCollection(AttributeCollection $collection)
    {
        parent::prepareAttributesCollection($collection);
        $collection->add(new Attribute('oTypeIcons', $this->getTypeIcons(), Attribute::TYPE_ARRAY));
        $collection->add(new Attribute('sFilePath', $this->getOption('file_source')));
        $collection->add(new Attribute('sDeleteRoute', $this->getOption('delete_route')));
        $collection->add(new Attribute('iMaxUploadSize', $this->getOption('max_upload_size')));
        $collection->add(new Attribute('sCreateFolderRoute', $this->getOption('create_folder_route')));
    }
}
