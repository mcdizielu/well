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
namespace WellCommerce\Bundle\OrderBundle\Form\Front;

use WellCommerce\Bundle\CoreBundle\Form\AbstractFormBuilder;
use WellCommerce\Component\Form\Elements\FormInterface;

/**
 * Class ConfirmationFormBuilder
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ConfirmationFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'front.confirmation';
    }
    
    public function buildForm(FormInterface $form)
    {
        $form->addChild($this->getElement('text_area', [
            'name'  => 'comment',
            'rows'  => 5,
            'cols'  => 20,
            'label' => $this->trans('order.label.comment'),
        ]));
        
        $form->addChild($this->getElement('checkbox', [
            'name'    => 'conditionsAccepted',
            'label'   => $this->trans('order.label.accept_conditions'),
            'default' => false,
            'comment' => $this->trans('order.label.accept_conditions'),
        ]));
        
        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
}
