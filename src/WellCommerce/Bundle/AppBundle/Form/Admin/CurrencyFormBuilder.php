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
namespace WellCommerce\Bundle\AppBundle\Form\Admin;

use Symfony\Component\Intl\Intl;
use WellCommerce\Bundle\CoreBundle\Form\AbstractFormBuilder;
use WellCommerce\Component\Form\Elements\FormInterface;

/**
 * Class CurrencyFormBuilder
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
class CurrencyFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'admin.currency';
    }
    
    public function buildForm(FormInterface $form)
    {
        $requiredData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'required_data',
            'label' => 'common.fieldset.general',
        ]));
        
        $requiredData->addChild($this->getElement('select', [
            'name'    => 'code',
            'label'   => 'currency.label.code',
            'options' => $this->getCurrenciesToSelect(),
            'default' => $this->getRequestHelper()->getCurrentCurrency(),
        ]));
        
        $requiredData->addChild($this->getElement('checkbox', [
            'name'    => 'enabled',
            'label'   => 'common.label.enabled',
            'comment' => 'currency.comment.enabled',
        ]));
        
        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
    
    private function getCurrenciesToSelect()
    {
        return Intl::getCurrencyBundle()->getCurrencyNames();
    }
}
