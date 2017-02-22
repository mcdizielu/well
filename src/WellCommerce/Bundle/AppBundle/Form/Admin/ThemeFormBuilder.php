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
 * Class ThemeFormBuilder
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
class ThemeFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'admin.theme';
    }
    
    public function buildForm(FormInterface $form)
    {
        $themeFolders = $this->get('theme.locator')->getThemeFolders();
        
        $requiredData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'required_data',
            'label' => 'common.fieldset.general'
        ]));
        
        $requiredData->addChild($this->getElement('text_field', [
            'name'  => 'name',
            'label' => 'common.label.name',
            'rules' => [
                $this->getRule('required')
            ],
        ]));
        
        $requiredData->addChild($this->getElement('select', [
            'name'    => 'folder',
            'label'   => 'theme.label.folder',
            'comment' => 'theme.comment.folder',
            'options' => $themeFolders,
            'default' => reset($themeFolders)
        ]));
        
        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
}
