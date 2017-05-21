<?php

namespace WellCommerce\Bundle\AppBundle\Service\ReCaptcha\Configuration;

use WellCommerce\Bundle\AppBundle\Service\System\Configuration\AbstractSystemConfigurator;
use WellCommerce\Component\Form\Elements\FormInterface;
use WellCommerce\Component\Form\FormBuilderInterface;

/**
 * Class ReCaptchaConfigurator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ReCaptchaConfigurator extends AbstractSystemConfigurator
{
    public function getAlias(): string
    {
        return 'recaptcha';
    }
    
    public function addFormFields(FormBuilderInterface $builder, FormInterface $form)
    {
        $generalData = $form->addChild($builder->getElement('nested_fieldset', [
            'name'  => 'recaptcha',
            'label' => 'recaptcha.fieldset.settings',
        ]));
        
        $generalData->addChild($builder->getElement('checkbox', [
            'name'  => 'enabled',
            'label' => 'common.label.enabled',
        ]))->setValue($this->getParameter('enabled'));
        
        $generalData->addChild($builder->getElement('text_field', [
            'name'  => 'siteKey',
            'label' => 'recaptcha.label.site_key',
        ]))->setValue($this->getParameter('siteKey'));
        
        $generalData->addChild($builder->getElement('text_field', [
            'name'  => 'secretKey',
            'label' => 'recaptcha.label.secret_key',
        ]))->setValue($this->getParameter('secretKey'));
    }
    
    public function getDefaults(): array
    {
        return [
            'siteKey'   => '',
            'secretKey' => '',
            'enabled'   => false,
        ];
    }
}
