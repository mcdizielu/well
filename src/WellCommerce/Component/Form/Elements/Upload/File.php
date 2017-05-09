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

namespace WellCommerce\Component\Form\Elements\Upload;

use Symfony\Component\OptionsResolver\OptionsResolver;
use WellCommerce\Component\Form\Elements\AbstractField;
use WellCommerce\Component\Form\Elements\Attribute;
use WellCommerce\Component\Form\Elements\AttributeCollection;
use WellCommerce\Component\Form\Elements\ElementInterface;

/**
 * Class File
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class File extends AbstractField implements ElementInterface
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        
        $resolver->setRequired([
            'load_route',
            'upload_route',
            'session_name',
            'session_id',
        ]);
        
        $resolver->setDefaults([
            'load_route'             => 'admin.media.grid',
            'upload_route'           => 'admin.media.add',
            'repeat_min'             => 0,
            'repeat_max'             => ElementInterface::INFINITE,
            'limit'                  => 1000,
            'file_types_description' => 'file_types_description',
            'file_types'             => ['jpg', 'jpeg', 'png', 'gif'],
        ]);
        
        $resolver->setAllowedTypes('session_id', 'string');
        $resolver->setAllowedTypes('session_name', 'string');
        $resolver->setAllowedTypes('file_types_description', 'string');
        $resolver->setAllowedTypes('file_types', 'array');
    }
    
    /**
     * {@inheritdoc}
     */
    public function prepareAttributesCollection(AttributeCollection $collection)
    {
        parent::prepareAttributesCollection($collection);
        
        $collection->add(new Attribute('sSessionName', $this->getOption('session_name')));
        $collection->add(new Attribute('sSessionId', $this->getOption('session_id')));
        $collection->add(new Attribute('iLimit', $this->getOption('limit'), Attribute::TYPE_INTEGER));
        $collection->add(new Attribute('asFileTypes', $this->getOption('file_types'), Attribute::TYPE_ARRAY));
        $collection->add(new Attribute('sFileTypesDescription', $this->getOption('file_types_description')));
        $collection->add(new Attribute('sLoadRoute', $this->getOption('load_route')));
        $collection->add(new Attribute('sUploadRoute', $this->getOption('upload_route')));
        $collection->add(new Attribute('oRepeat', $this->prepareRepetitions(), Attribute::TYPE_ARRAY));
    }
    
    protected function getTypeIcons(): array
    {
        return [
            'cdup'      => 'images/icons/filetypes/cdup.png',
            'unknown'   => 'images/icons/filetypes/unknown.png',
            'directory' => 'images/icons/filetypes/directory.png',
            'gif'       => 'images/icons/filetypes/image.png',
            'png'       => 'images/icons/filetypes/image.png',
            'jpg'       => 'images/icons/filetypes/image.png',
            'bmp'       => 'images/icons/filetypes/image.png',
            'txt'       => 'images/icons/filetypes/text.png',
            'doc'       => 'images/icons/filetypes/text.png',
            'rtf'       => 'images/icons/filetypes/text.png',
            'odt'       => 'images/icons/filetypes/text.png',
            'htm'       => 'images/icons/filetypes/document.png',
            'html'      => 'images/icons/filetypes/document.png',
            'php'       => 'images/icons/filetypes/document.png',
        ];
    }
}
