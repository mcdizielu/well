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
 * Class UserFormBuilder
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
class UserFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'admin.user';
    }
    
    public function buildForm(FormInterface $form)
    {
        $requiredData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'required_data',
            'label' => 'common.fieldset.general'
        ]));

        $requiredData->addChild($this->getElement('text_field', [
            'name'  => 'firstName',
            'label' => 'user.label.first_name',
            'rules' => [
                $this->getRule('required')
            ],
        ]));

        $requiredData->addChild($this->getElement('text_field', [
            'name'  => 'lastName',
            'label' => 'user.label.last_name',
            'rules' => [
                $this->getRule('required')
            ],
        ]));

        $requiredData->addChild($this->getElement('text_field', [
            'name'  => 'username',
            'label' => 'user.label.username',
            'rules' => [
                $this->getRule('required')
            ],
        ]));

        $requiredData->addChild($this->getElement('text_field', [
            'name'  => 'email',
            'label' => 'user.label.email',
            'rules' => [
                $this->getRule('required')
            ],
        ]));

        $requiredData->addChild($this->getElement('multi_select', [
            'name'        => 'groups',
            'label'       => 'user.label.user_group',
            'options'     => $this->get('user_group.dataset.admin')->getResult('select'),
            'transformer' => $this->getRepositoryTransformer('collection', $this->get('user_group.repository')),
        ]));

        $apiData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'api_data',
            'label' => 'user.fieldset.api_access'
        ]));

        $apiData->addChild($this->getElement('text_field', [
            'name'  => 'apiKey',
            'label' => 'user.label.api_key',
        ]));

        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
}
