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
namespace WellCommerce\Bundle\OrderBundle\Form\Admin;

use WellCommerce\Bundle\CoreBundle\Form\AbstractFormBuilder;
use WellCommerce\Component\Form\Elements\FormInterface;

/**
 * Class OrderStatusHistoryFormBuilder
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
class OrderStatusHistoryFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'admin.order_status_history';
    }
    
    public function buildForm(FormInterface $form)
    {
        $orderStatuses = $this->get('order_status.dataset.admin')->getResult('select');
    
        $requiredData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'status_data',
            'label' => 'common.fieldset.general'
        ]));
    
        $requiredData->addChild($this->getElement('select', [
            'name'        => 'orderStatus',
            'label'       => 'order_status_history.label.order_status',
            'options'     => $orderStatuses,
            'transformer' => $this->getRepositoryTransformer('entity', $this->get('order_status.repository'))
        ]));
    
        $requiredData->addChild($this->getElement('text_area', [
            'name'  => 'comment',
            'rows'  => 10,
            'label' => 'order_status_history.label.comment'
        ]));
    
        $requiredData->addChild($this->getElement('checkbox', [
            'name'    => 'notify',
            'label'   => 'order_status_history.label.nofity',
            'default' => 1
        ]));

        $form->addChild($this->getElement('submit', [
            'name'  => 'add_order_status_history',
            'label' => 'order_status_history.button.change',
        ]));
        
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
}
