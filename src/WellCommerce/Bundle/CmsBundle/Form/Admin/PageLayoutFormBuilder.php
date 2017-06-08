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

namespace WellCommerce\Bundle\CmsBundle\Form\Admin;

use WellCommerce\Bundle\CoreBundle\Form\AbstractFormBuilder;
use WellCommerce\Component\Form\Elements\FormInterface;

/**
 * Class PageLayoutFormBuilder
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class PageLayoutFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'admin.page_layout';
    }
    
    public function buildForm(FormInterface $form)
    {
        $mainData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'main_data',
            'label' => 'common.fieldset.general',
        ]));
        
        $producer = $mainData->addChild($this->getElement('select', [
            'name'        => 'producer',
            'label'       => 'common.label.producer',
            'options'     => $this->get('producer.dataset.admin')->getResult('select', ['limit' => 10000]),
            'transformer' => $this->getRepositoryTransformer('entity', $this->get('producer.repository')),
        ]));
        
        $mainData->addChild($this->getElement('select', [
            'name'         => 'producer_collection',
            'label'        => $this->trans('common.label.producer_collection'),
            'transformer'  => $this->getRepositoryTransformer('entity', $this->get('producer_collection.repository')),
            'dependencies' => [
                $this->getDependency('exchange_options', [
                    'field'              => $producer,
                    'load_options_route' => 'admin.producer_collection.get_collections_by_producer_id.ajax.index',
                    'form'               => $form,
                ]),
            ],
        ]));
    }
}
