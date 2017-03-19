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

use WellCommerce\Bundle\CoreBundle\Form\AbstractFormBuilder;
use WellCommerce\Component\Form\Elements\FormInterface;

/**
 * Class UserResetPasswordFormBuilder
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class UserResetPasswordFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'admin.user_reset_password';
    }
    
    public function buildForm(FormInterface $form)
    {
        $form->addChild($this->getElement('text_field', [
            'name'  => 'username',
            'label' => 'user.label.username',
            'rules' => [
                $this->getRule('required')
            ],
        ]));

        $form->addChild($this->getElement('submit', [
            'name'  => 'reset_password',
            'label' => 'user.button.reset_password',
        ]));

        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
}
