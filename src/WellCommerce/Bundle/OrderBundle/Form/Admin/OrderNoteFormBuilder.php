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
 * Class OrderNoteFormBuilder
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class OrderNoteFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'admin.order_note';
    }
    
    public function buildForm(FormInterface $form)
    {
        $requiredData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'note_data',
            'label' => 'common.fieldset.general',
        ]));
        
        $requiredData->addChild($this->getElement('text_area', [
            'name'  => 'content',
            'rows'  => 10,
            'label' => 'order_note.label.content',
        ]));
        
        $form->addChild($this->getElement('submit', [
            'name'  => 'add_order_note',
            'label' => 'order_note.button.save',
        ]));
        
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
}
