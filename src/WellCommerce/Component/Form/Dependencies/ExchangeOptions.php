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

namespace WellCommerce\Component\Form\Dependencies;

use Symfony\Component\OptionsResolver\OptionsResolver;
use WellCommerce\Component\Form\Elements\ElementInterface;
use WellCommerce\Component\Form\Elements\Form;

/**
 * Class ExchangeOptions
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ExchangeOptions extends AbstractDependency
{
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'field',
            'form',
            'load_options_route',
        ]);
        
        $resolver->setAllowedTypes('field', ElementInterface::class);
        $resolver->setAllowedTypes('form', Form::class);
        $resolver->setAllowedTypes('load_options_route', 'string');
    }
    
    public function renderJs()
    {
        $javascriptType = $this->getJavascriptType();
        $fieldName      = $this->getField()->getName();
        $formName       = $this->getForm()->getName();
        
        return sprintf("new GFormDependency(GFormDependency.%s, '%s.%s', '%s')",
            $javascriptType,
            $formName,
            $fieldName,
            $this->options['load_options_route']
        );
    }
    
    public function getJavascriptType()
    {
        return 'EXCHANGE_OPTIONS';
    }
}
