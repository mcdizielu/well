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
namespace WellCommerce\Bundle\AppBundle\Form\Front;

use WellCommerce\Bundle\CoreBundle\Form\AbstractFormBuilder;
use WellCommerce\Component\Form\Elements\FormInterface;

/**
 * Class ClientChangePasswordFormBuilder
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ClientChangePasswordFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'front.client_change_password';
    }
    
    public function buildForm(FormInterface $form)
    {
        $clientDetails = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'clientDetails',
            'label' => 'client.heading.client_details',
        ]));

        $clientDetails->addChild($this->getElement('password', [
            'name'  => 'clientDetails.hashedPassword',
            'label' => 'client.label.new_password',
        ]));

        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
}
